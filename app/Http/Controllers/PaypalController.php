<?php

/**
 * Class PaypalController
 *
 * @category Doctry
 *
 * @package Doctry
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */

namespace App\Http\Controllers;

use App\Appointment;
use Illuminate\Http\Request;
use App\Package;
use App\Order;
use App\User;
use App\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\OrderMeta;
use Srmklive\PayPal\Services\ExpressCheckout;
use App\SiteManagement;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\Helper;
use Auth;
use DB;
use App\Mail\DoctorEmailMailable;
use App\Mail\GeneralEmailMailable;
use Carbon\Carbon;
use function Opis\Closure\serialize;

/**
 * Class PaypalController
 *
 */
class PaypalController extends Controller
{

    /**
     * Defining scope of the variable
     *
     * @access public
     * @var    array $provider
     */
    protected $provider;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->provider = new ExpressCheckout();
    }

    /**
     * Get index.
     *
     * @param mixed $request $req->attr
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        if (Auth::user()) {
            $response = [];
            if (session()->has('code')) {
                $response['code'] = session()->get('code');
                session()->forget('code');
            }
            if (session()->has('message')) {
                $response['message'] = session()->get('message');
                session()->forget('message');
            }
            $error_code = session()->get('code');
            Session::flash('payment_message', $response);
            $role_type = Helper::getRoleTypeByUserID(Auth::user()->id);
            return Redirect::to($role_type . '/dashboard');
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Get express checkout.
     *
     * @param mixed $request $req->attr
     *
     * @return \Illuminate\Http\Response
     */
    public function getExpressCheckout(Request $request)
    {
        if (Auth::user()) {
            $id = session()->get('product_id');
            $type = session()->get('type');
            $order = new Order();
            $order->status = 'pending';
            $order->payment_gateway = 'paypal';
            if ($type == 'appointment') {
                $appointment = Appointment::find($id);
                $order->appointment_date = $appointment->appointment_date;
            }
            $order->user()->associate(Auth::user()->id);
            $order->save();
            $order_id = DB::getPdo()->lastInsertId();
            $latest_order = Order::find($order_id);
            session()->put(['order_id' => $order_id]);
            $order_type = new OrderMeta();
            $order_type->meta_key = 'type';
            $order_type->meta_value = $type;
            $latest_order->orderMeta()->save($order_type);
            if ($type == 'appointment') {
                $appointment_data = array();
                $appointment = Appointment::find($id);
                if (!empty($appointment->toArray())) {
                    foreach ($appointment->toArray() as $appointment_key => $appointment_value) {
                        $appointment_data[$appointment_key] = $appointment_value;
                    }
                    $appointment_meta = new OrderMeta();
                    $appointment_meta->meta_key = 'appointment';
                    $appointment_meta->meta_value = serialize($appointment_data);
                    $latest_order->orderMeta()->save($appointment_meta);
                }
            } else if ($type == 'package') {
                $package_data = array();
                $package = Package::find($id)->toArray();
                if (!empty($package)) {
                    foreach ($package as $package_key => $package_value) {
                        $package_data[$package_key] = $package_value;
                    }
                    $package_meta = new OrderMeta();
                    $package_meta->meta_key = 'package';
                    $package_meta->meta_value = serialize($package_data);
                    $latest_order->orderMeta()->save($package_meta);
                }
            }

            $settings = SiteManagement::getMetaValue('paypal_settings');
            $payment_mode = !empty($settings) && !empty($settings['enable_sandbox']) ? $settings['enable_sandbox'] : 'false';
            if ($payment_mode == 'true') {
                if (
                    empty(env('PAYPAL_SANDBOX_API_USERNAME'))
                    && empty(env('PAYPAL_SANDBOX_API_PASSWORD'))
                    && empty(env('PAYPAL_SANDBOX_API_SECRET'))
                ) {
                    Session::flash('error', trans('lang.paypal_empty_credentials'));
                    return Redirect::back();
                }
            } elseif ($payment_mode == 'false') {
                if (
                    empty(env('PAYPAL_LIVE_API_USERNAME'))
                    && empty(env('PAYPAL_LIVE_API_PASSWORD'))
                    && empty(env('PAYPAL_LIVE_API_SECRET'))
                ) {
                    Session::flash('error', trans('lang.paypal_empty_credentials'));
                    return Redirect::back();
                }
            }
            $settings = SiteManagement::getMetaValue('payment_settings');
            $currency = !empty($settings['currency']) ? $settings['currency'] : 'USD';
            $supportedCurrencies = array_column(Helper::getPaypalSupportedCurrencies(), 'code');
            if (in_array($currency, $supportedCurrencies)) {
                if (Auth::user()) {
                    if (session()->has('source_url')) {
                        $source_url = session()->get('source_url');
                    }
                    //$recurring = ($request->get('mode') === 'recurring') ? true : false;
                    $recurring = false;
                    $success = true;
                    $cart = $this->getCheckoutData($recurring, $success);
                    $payment_detail = array();

                    // Guest checkout
                    // $options = [
                    //     'SOLUTIONTYPE' => 'Sole',
                    // ];
                    
                    // $response = $provider->addOptions($options)->setExpressCheckout($data);

                    try {
                        $response = $this->provider->setCurrency($currency)->setExpressCheckout($cart, $recurring);
                        if ($response['ACK'] == 'Failure') {
                            Session::flash('error', $response['L_LONGMESSAGE0']);
                            return Redirect::back();
                            // return Redirect::to($source_url);
                        }
                        return redirect($response['paypal_link']);
                    } catch (\Exception $e) {
                        $invoice = $this->createInvoice($cart, 'Invalid', $payment_detail);
                        session()->put(['code' => 'danger', 'message' => "Error processing PayPal payment for Order $invoice->id!"]);
                    }
                } else {
                    return Redirect::to('/404');
                }
            } else {
                Session::flash('error', trans('lang.currency_not_supported_paypal'));
                return Redirect::back();
            }
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Get Express Checkout Success.
     *
     * @param mixed $request $req->attr
     *
     * @return \Illuminate\Http\Response
     */
    public function getExpressCheckoutSuccess(Request $request)
    {
        if (Auth::user()) {
            //$recurring = ($request->get('mode') === 'recurring') ? true : false;
            $recurring = false;
            $token = $request->get('token');
            $PayerID = $request->get('PayerID');
            $success = false;
            $cart = $this->getCheckoutData($recurring, $success);
            // Verify Express Checkout Token
            $response = $this->provider->getExpressCheckoutDetails($token);
            if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
                if ($recurring === true) {
                    $response = $this->provider->createMonthlySubscription($response['TOKEN'], 9.99, $cart['subscription_desc']);
                    if (!empty($response['PROFILESTATUS']) && in_array($response['PROFILESTATUS'], ['ActiveProfile', 'PendingProfile'])) {
                        $status = 'Processed';
                    } else {
                        $status = 'Invalid';
                    }
                } else {
                    // Perform transaction on PayPal
                    // dd($cart);
                    $payment_status = $this->provider->doExpressCheckoutPayment($cart, $token, $PayerID);
                    $status = !empty($payment_status['PAYMENTINFO_0_PAYMENTSTATUS']) ? $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'] : 'Processed';
                }
                $payment_detail = array();
                $payment_detail['payer_name'] = $response['FIRSTNAME'] . " " . $response['LASTNAME'];
                $payment_detail['payer_email'] = $response['EMAIL'];
                $payment_detail['seller_email'] = !empty($response['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID']) ? $response['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID'] : '';
                $payment_detail['currency_code'] = $response['CURRENCYCODE'];
                $payment_detail['payer_status'] = $response['PAYERSTATUS'];
                $payment_detail['transaction_id'] = !empty($payment_status['PAYMENTINFO_0_TRANSACTIONID']) ? $payment_status['PAYMENTINFO_0_TRANSACTIONID'] : '';
                $payment_detail['sales_tax'] = $response['TAXAMT'];
                $payment_detail['invoice_id'] = $response['INVNUM'];
                $payment_detail['shipping_amount'] = $response['SHIPPINGAMT'];
                $payment_detail['handling_amount'] = $response['HANDLINGAMT'];
                $payment_detail['insurance_amount'] = $response['INSURANCEAMT'];
                $payment_detail['paypal_fee'] = !empty($payment_status['PAYMENTINFO_0_FEEAMT']) ? $payment_status['PAYMENTINFO_0_FEEAMT'] : '';
                $payment_detail['payment_date'] = $payment_status['TIMESTAMP'];
                $payment_detail['product_qty'] = $cart['items'][0]['qty'];
                $invoice = $this->createInvoice($cart, $status, $payment_detail);
                if ($invoice->paid) {
                    session()->put(['code' => 'success', 'message' => "Thank you for your subscription"]);
                } else {
                    session()->put(['code' => 'danger', 'message' => "Error processing PayPal payment for Order $invoice->id!"]);
                }
                return redirect('paypal/redirect-url');
            } else {
                return Redirect::to('/404');
            }
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Get Express Checkout Success.
     *
     * @param mixed $recurring $recurring
     * @param mixed $success   $recurring
     *
     * @return \Illuminate\Http\Response
     */
    protected function getCheckoutData($recurring, $success)
    {
        if (Auth::user()) {
            if (session()->has('product_id')) {
                $id = session()->get('product_id');
                $name = session()->get('name');
                $price = session()->get('price');
                $user_id = Auth::user()->id;
                $random_number = Helper::generateRandomCode(4);
                $unique_code = strtoupper($random_number);
                $data = [];
                $order_id = Order::all()->count() + 1;
                if ($recurring === true) {
                    // $data['items'] = [
                    //     [
                    //         'name' => 'Monthly Subscription ' . config('paypal.invoice_prefix') . ' #' . $order_id,
                    //         'price' => 0,
                    //         'qty' => 1,
                    //     ],
                    // ];
                    // $data['return_url'] = url('/paypal/ec-checkout-success?mode=recurring');
                    // $data['subscription_desc'] = 'Monthly Subscription ' . config('paypal.invoice_prefix') . ' #' . $order_id;
                } else {
                    $data['items'] = [
                        [
                            'product_id' => $id,
                            'name' => $name,
                            'price' => $price,
                            'subscriber_id' => $user_id,
                            'qty' => 1,
                        ],

                    ];
                    $data['return_url'] = url('/paypal/ec-checkout-success');
                }
                $data['invoice_id'] = config('paypal.invoice_prefix') . '_' . $unique_code . '_' . $order_id;
                $data['invoice_description'] = "Order #$order_id Invoice";
                $data['cancel_url'] = url('/');
                $total = 0;
                $data['total'] = $price;
                return $data;
            } else {
                return Redirect::to('/404');
            }
        } else {
            Session::flash('message', trans('lang.product_id_not_found'));
            return Redirect::to('/');
        }
    }

    /**
     * Create invoice
     *
     * @param mixed $cart           cart
     * @param mixed $status         status
     * @param mixed $payment_detail payment_detail
     *
     * @return \Illuminate\Http\Response
     */
    protected function createInvoice($cart, $status, $payment_detail)
    {
        if (session()->has('product_id') && session()->has('type')) {
            $type = session()->get('type');
            $id = session()->get('product_id');
            $order_id = session()->get('order_id');
            $new_order = Order::find($order_id);
            $new_order->status = 'completed';
            $new_order->save();
            if (!empty($payment_detail)) {
                foreach ($payment_detail as $key => $value) {
                    $meta = new OrderMeta();
                    $meta->meta_key = $key;
                    $meta->meta_value = $value;
                    $new_order->orderMeta()->save($meta);
                }
            }
            if ($type == 'appointment') {
                $appointment = Appointment::find($id);
                $appointment->status = 'accepted';
                $appointment->save();
                // appointment mail
                $hospital = User::findOrFail($appointment->hospital_id);
                $doctor = User::findOrFail($appointment->user_id);
                if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                    $email_params = array();
                    $template = DB::table('email_types')->select('id')->where('email_type', 'user_email_appointment_request_approved')->get()->first();
                    if (!empty($template->id)) {
                        $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                        $email_params['user_name'] = Helper::getUserName(Auth::user()->id);
                        $email_params['hospital_name'] = Helper::getUserName($hospital->id);
                        $email_params['hospital_link'] = url('profile/' . $hospital->slug);
                        $email_params['doctor_name'] = Helper::getUserName($doctor->id);
                        $email_params['doctor_link'] = url('profile/' . $doctor->slug);
                        $email_params['appointment_date_time'] = Carbon::parse($appointment->appointment_date)->format('d M, Y') . ' ' . $appointment->appointment_time;
                        $email_params['description'] = $appointment->comments;
                        Mail::to(Auth::user()->email)
                            ->send(
                                new GeneralEmailMailable(
                                    'user_email_appointment_request_approved',
                                    $template_data,
                                    $email_params
                                )
                            );
                    }
                }
            } else if ($type == 'package') {
                $package = Package::find($id)->toArray();
                // Package Mail
                $package = Package::find($id);
                $option = !empty($package->options) ? unserialize($package->options) : '';
                $expiry = !empty($new_order) ? $new_order->created_at->addDays($option['duration']) : '';
                $expiry_date = !empty($expiry) ? Carbon::parse($expiry)->toDateTimeString() : '';
                $user = User::find(Auth::user()->id);
                $user->package_expiry = $expiry_date;
                $user->save();
                // send mail
                if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                    $email_params = array();
                    $template = DB::table('email_types')->select('id')->where('email_type', 'doctor_email_package_subscribed')->get()->first();

                    if (!empty($template->id)) {
                        $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                        $email_params['doctor_name'] = Helper::getUserName(Auth::user()->id);
                        $email_params['pkg_title'] = $package->title;
                        $email_params['amount'] = $package->cost;
                        $email_params['date'] = Carbon::parse($new_order->created_at)->format('M d, Y');
                        $email_params['expiry_date'] = !empty($expiry) ? Carbon::parse($expiry)->format('M d, Y') : '';
                        Mail::to(Auth::user()->email)
                            ->send(
                                new DoctorEmailMailable(
                                    'doctor_email_package_subscribed',
                                    $template_data,
                                    $email_params
                                )
                            );
                    }
                }
            }
        }
        session()->forget('product_id');
        session()->forget('price');
        session()->forget('name');
        return $new_order;
    }
}
