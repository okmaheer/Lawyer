<html>
    <head>
        <style>
            @page {
                margin: 10px 0px 50px 0px;
            }
            *{box-sizing: border-box;}
            header {
                top: -30px;
                left: 0px;
                right: 0px;
                height: 180px;
                position:absolute;
                border-radius:5px;
                font-family: sans-serif;
                background: url('.$border_image.');
                background-position: top;
                background-size: 100% 100%;
                background-repeat: no-repeat;
            }
            table { border-collapse: collapse; }
        </style>
    </head>
    <body style="font-family: sans-serif; margin-top:0;padding-top:30px;position:relative;">
        <header><img src="{{$header_image}}" style=" display:block; position:absolute;top:0px;right:0;width:100%; height:180px;"></header>
        <div style="width:100%; display: inline-block; text-align:center; font-family: sans-serif;padding:0 0 30px;">
            <table style="width:96%; margin:0 auto 0;">
                <tr style="text-align:left;">
                    <td width="70%">

                        @if(!empty($hospital_detail['avatar']))
                            <h1 style="font-size: 26px;line-height: 26px;margin: 0 0 10px; font-weight: 500; color: #3d4461;" ><img style="max-width:125px;border-radius:5px;" src="{{$hospital_detail['avatar']}}" ></h1>
                        @endif
        
                        @if(!empty($hospital_detail['name']))
                            <h4 style="font-size: 1.3em;line-height: 1.2;">{{$hospital_detail['name']}}</h4>
                        @endif
            
                        @if( !empty($hospital_detail['address']) )
                            <span style="margin-top: 6px; line-height: 20px; font-size: 14px; display: block;text-decoration: none;">{{{$hospital_detail['address']}}}</span>
                        @endif
            
                        @if( !empty($hospital_detail['location'] ) )
                            <span style="margin-top: 0px; line-height: 20px; font-size: 14px; display: block;text-decoration: none;">{{$hospital_detail['location'] }}</span>
                        @endif
            
                        @if( !empty($hospital_detail['email']) )
                            <a style="margin-top: 6px; line-height: 20px; font-size: 14px; display: block;color:#3fabf3;text-decoration: none;" href="mailto:{{($hospital_detail['email'])}}">{{$hospital_detail['email']}}</a>
                        @endif
                    </td>
                </tr>
            </table>
            <table style="width:96%; margin:20px auto 0;">
                <tr style="text-align:left;">
                    @if( !empty($saved_patient_detail['patient_name'])) 
                        <td width="100%" style="text-align:left;box-sizing: border-box;"><span style="display: inline-block; padding: 7px 15px 7px; line-height: 1.3em;width: 100%; font-size: 14px; border-bottom: 1px solid #ddd; margin: 0 1% 10px;">{{ trans('lang.name') }}: {{$saved_patient_detail['patient_name']}}</span></span></td>
                    @endif

                    @if( !empty($saved_patient_detail['age']))
                        <td width="100%" style="text-align:left;box-sizing: border-box;"><span style="display: inline-block; padding: 7px 15px 7px; line-height: 1.3em;width: 100%; font-size: 14px; border-bottom: 1px solid #ddd; margin: 0 1% 10px;">{{ trans('lang.age') }}: {{$saved_patient_detail['age']}}</span></td>
                    @endif
                </tr>
                @if ( !empty($saved_patient_detail['gender']) || !empty( $saved_patient_detail['address'] ))
                    <tr style="text-align:left;">
                        @if( !empty($saved_patient_detail['gender']))
                            <td width="100%" style="text-align:left;box-sizing: border-box;"><span style="display: inline-block; padding: 7px 15px 7px; line-height: 1.3em;width: 100%; font-size: 14px; border-bottom: 1px solid #ddd; margin: 0 1% 10px;">{{ trans('lang.gender') }}: {{$saved_patient_detail['gender']}}</span></td>
                        @endif

                        @if( !empty( $saved_patient_detail['address'] ))
                            <td width="100%" style="text-align:left;box-sizing: border-box;"><span style="display: inline-block; padding: 7px 15px 7px; line-height: 1.3em;width: 100%; font-size: 14px; border-bottom: 1px solid #ddd; margin: 0 1% 10px;">{{ trans('lang.address') }}: {{ $saved_patient_detail['address'] }}</span></td>
                        @endif
                    </tr>
                @endif
                @if ( !empty($martial_status) || !empty($saved_childhood_illness))
                    <tr style="text-align:left;">
                        @if( !empty($martial_status))
                            <td width="100%" style="text-align:left;box-sizing: border-box;"><span style="display: inline-block; padding: 7px 15px 7px; line-height: 1.3em;width: 100%; font-size: 14px; border-bottom: 1px solid #ddd; margin: 0 1% 10px;">{{ trans('lang.martial_status') }}: {{$martial_status}}</span></td>
                        @endif
                        @if( !empty($saved_childhood_illness))
                            @php
                                $illnesses_name		= '';
                                $counter_illnesses	= 0;
                                $total_illnesses		= count($saved_childhood_illness);

                                foreach ($saved_childhood_illness as $illness) {
                                    $counter_illnesses++;
                                    $illnesses_name	.= $total_illnesses > $counter_illnesses ? $illness.', ' : $illness;
                                }
                            @endphp
                            <td width="100%" style="text-align:left;box-sizing: border-box;">
                                <span style="display: inline-block; padding: 7px 15px 7px; line-height: 1.3em;width: 100%; font-size: 14px; border-bottom: 1px solid #ddd; margin: 0 1% 10px;">
                                    {{ trans('lang.childhood_illness') }}: {{$illnesses_name}}
                                </span>
                            </td>
                        @endif
                    </tr>
                @endif
            
                @if( !empty($saved_diseases) || !empty($saved_vital_sign))
                    <tr style="text-align:left;">
                        @if( !empty($saved_diseases))
                            @php
                                $diseases_name		= '';
                                $counter_diseases	= 0;
                                $total_diseases		= count($saved_diseases);

                                foreach ($saved_diseases as $disease) {
                                    $counter_diseases++;
                                    $diseases_name	.= $total_diseases > $counter_diseases ? $disease.', ' : $disease;
                                }
                            @endphp
                            <td width="100%" style="text-align:left;box-sizing: border-box;"><span style="display: inline-block; padding: 7px 15px 7px; line-height: 1.3em;width: 100%; font-size: 14px; border-bottom: 1px solid #ddd; margin: 0 1% 10px;">{{ trans('lang.diseases') }}: {{$diseases_name}}</span></td>

                        @endif
                        @if ( !empty( $saved_vital_sign ) )
                            @php
                                $counter_sign		= 0;
                                $vital_signs_name	= '';
                                $total_sign			= count($saved_vital_sign);
                                foreach($saved_vital_sign as $key => $val ) { 
                                    $counter_sign++;
                                    if( !empty($val) ) {
                                        $sing_val			= !empty($val['pivot']['value']) ? $val['pivot']['value'] : '';
                                        $vital_signs		= !empty($val['title']) ? $val['title']. '('.$sing_val.')' : '';
                                        $vital_signs_name	.= $total_sign > $counter_sign ? $vital_signs.', ' : $vital_signs;
                                    }
                                }
                            @endphp
                        <td width="100%" style="text-align:left;box-sizing: border-box;"><span style="display: inline-block; padding: 7px 15px 7px; line-height: 1.3em;width: 100%; font-size: 14px; border-bottom: 1px solid #ddd; margin: 0 1% 10px;">{{ trans('lang.vital_signs') }}: {{$vital_signs_name}}</span></td>
                        @endif
                    </tr>
                @endif
            </table>
            @if ( !empty($saved_prescription['history'] ) ) 
                <em style="font-size: 20px; line-height: 1.3em; color: #3d4461; display: block; width: 95%; margin: 20px auto; text-align: left; font-style: normal;">{{ trans('lang.diagnosis') }}</em>
                <p style="text-align:left; font-size: 14px; line-height:1.5em; width: 95%; margin:0 auto;">{{$saved_prescription['history']}}</p>
            @endif
            @if ( !empty($saved_medications) ) 
                <em style="font-size: 20px; line-height: 1.3em; color: #3d4461; display: block; width: 95%; margin: 20px auto; text-align: left; font-style: normal;">{{ trans('lang.medications') }}</em>
                <table style="width: 95%; margin: 0 auto; font-family: sans-serif;">
                    <thead>
                        <tr style="text-align: left; border-radius:5px 0 0;">
                            <th style="width:10%; padding: 15px 20px;background: #f5f5f5; font-size:14px;text-align: left;">{{ trans('lang.name') }}</th>
                            <th style="width:10%; padding: 15px 20px;background: #f5f5f5; font-size:14px;text-align: left;">{{ trans('lang.types') }}</th>
                            <th style="width:15%; padding: 15px 20px;background: #f5f5f5; font-size:14px;text-align: left;">{{ trans('lang.duration') }}</th>
                            <th style="width:15%; padding: 15px 20px;background: #f5f5f5; font-size:14px;text-align: left;">{{ trans('lang.usage') }}</th>
                            <th style="width:25%; padding: 15px 20px;background: #f5f5f5; font-size:14px;text-align: left;">{{ trans('lang.details') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saved_medications as $vals ) 
                            <tr>
                                @if (!empty($vals) )
                                    @php 
                                        $medi_type = \App\MedicineType::where('id', $vals['medicine_type_id'])->value('title');
                                        $medi_duration =  \App\MedicineDuration::where('id', $vals['medicine_duration_id'])->value('title');
                                        $medi_usage =  \App\MedicineUsage::where('id', $vals['medicine_usage_id'])->value('title');
                                    @endphp
                                    <td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">{{ $vals['title'] }}</td>
                                    <td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">{{ $medi_type }}</td>
                                    <td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">{{ $medi_duration }}</td>
                                    <td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">{{ $medi_usage }}</td>
                                    <td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">{{ $vals['comment'] }}</td>
                                @endif		
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            @if ( !empty($saved_laboratory_test) )
                @php
                    $tests_name		= '';
                    $counter_tests	= 0;
                    $total_tests	= count($saved_laboratory_test);

                    foreach ($saved_laboratory_test as $test) {
                        $counter_diseases++;
                        $tests_name	.= $total_tests > $counter_tests ? $test.', ' : $test;
                    }
                @endphp
                <em style="font-size: 20px; line-height: 1.3em; color: #c32c2c; display: block; width: 95%; margin: 20px auto; text-align: left; font-style: normal;">{{ trans('lang.required_lab_tests') }}</em>
                <p style="text-align:left; font-size: 14px; line-height:1.5em; width: 95%; margin:0 auto;">{{$tests_name}}</p>
                
            @endif
        </div>
        <footer style="text-align: center;margin-top:0;padding: 0 0 0;position:fixed; bottom:0;padding:0; min-height:100px;">
            <img src="{{$footer_image}}"  style=" display:block; position:absolute;bottom:-60px;left:0;width:100%; height:150px;">
        </footer>
    </body>
</html>