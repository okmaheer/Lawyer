<template>
    <div>
        <div class="amt-section-select">
            <input type="text" name="meta_title" class="form-control" :placeholder="trans('lang.meta_title')" v-model="form.pageMeta.meta_title">
        </div>
        <div class="amt-section-select">
            <textarea class="form-control" :placeholder="trans('lang.meta_desc')" v-model="form.pageMeta.meta_desc"></textarea>
        </div>
        <div class="form-group" >
            <div class="amt-element-title amt-element-titlecontent amt-subsection">
                <h6>{{ trans('lang.select_parent_type') }}:</h6>
                <div class="amt-subsection__content">
                    <div class="dc-radio">
                        <input type="radio" id="select-parent" v-model="form.parent_type" name="parent_type" value="custom_link">
                        <label for="select-parent">{{ trans('lang.custom_menu') }}</label>
                    </div>
                    <div class="dc-radio">
                        <input type="radio" id="select-paret" v-model="form.parent_type" name="parent_type" value="page">
                        <label for="select-paret">{{ trans('lang.pages') }}</label>
                    </div>
                </div>
            </div>
        </div>
         <!-- Custom Menus -->
        <div class="amt-section-select border-top-0" v-if="customMenus && customMenus.length > 0 && form.parent_type == 'custom_link'">
            <span class="dc-select">
                <select class="form-control" v-model="form.parent_id">
                    <option value='' selected>{{ trans('lang.select_parent') }}</option>
                    <option v-for="(menu, index) in customMenus" :key="index" :value="menu.custom_slug"> 
                        {{menu.custom_title}} 
                    </option>
                </select>
            </span>
        </div>
        <!-- End Custom Menus -->
        <div class="amt-section-select border-top-0" v-if="parentPages && parentPages.length > 0 && form.parent_type == 'page'">
            <span class="dc-select">
                <select class="form-control" v-model="form.parent_id">
                    <option value="" selected>{{ trans('lang.select_parent') }}</option>
                    <option v-for="(page, index) in parentPages" :key="index" :value="page.id"> 
                        {{page.title}} 
                    </option>
                </select>
            </span>
        </div>
        <div class="amt-switches-option">
            <div class="at-account-checkbox">
                <div class="at-on-off">
                    <input type="checkbox" id="show_page_title" v-model="form.pageMeta.show_page_title">
                    <label for="show_page_title"><i></i></label>
                </div>
                <p>{{ trans('lang.show_page_title') }}</p>
            </div>
        </div>
        <div class="amt-switches-option">
            <div class="at-account-checkbox">
                <div class="at-on-off">
                    <input type="checkbox" id="show_page" v-model="form.pageMeta.show_page">
                    <label for="show_page"><i></i></label>
                </div>
                <p>{{ trans('lang.add_menu_to_navbar') }}</p>
            </div>
        </div>
        <div class="amt-switches-option">
            <div class="at-account-checkbox">
                <div class="at-on-off">
                    <input type="checkbox" id="sidebar" v-model="form.pageMeta.sidebar">
                    <label for="sidebar"><i></i></label>
                </div>
                <p>{{ trans('lang.enable_disable_sidebar') }}</p>
            </div>
        </div>
        <div class="amt-switches-option">
            <span class="dc-select">
                <select class="form-control" v-model="form.pageMeta.sidebarOrder">
                    <option value="" selected>{{ trans('lang.select_sidebar_position') }}</option>
                    <option v-for="(order, index) in orders" :key="index+'-order'" :value="index"> 
                        {{order}} 
                    </option>
                </select>
            </span>
        </div>
    </div>
</template>
<script>
export default {
    props:['form','pageID', 'parentPages'],
    data() {
        return {
            baseURL: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
            orders:this.getOrderList(),
            customMenus:''
        }
    },
    methods:{
        getCustomMenus () {
            let self = this;
            axios.get(APP_URL + '/get-parent-menu-list')
            .then(function (response) {
                self.customMenus = response.data.parent_menus;
            });
        },
    },
    created () {
        this.getCustomMenus();
    } 
}
</script>