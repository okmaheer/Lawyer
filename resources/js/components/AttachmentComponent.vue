<template>
    <div>
        <div class="pageloader-outer" v-if="loading" v-cloak>
            <div class="dc-preloader-holder">
                <div class="dc-loader"></div>
            </div>
        </div>
        <div class="la-upload-holder">
            <vue-dropzone 
            :options="this.dropzoneOptions" 
            :id="this.id" 
            :useCustomSlot=true 
            :ref="this.img_ref" 
            v-on:vdropzone-error="failed" 
            v-on:vdropzone-thumbnail="validateDimentation"
            v-on:vdropzone-file-added="addedFile"
            v-on:vdropzone-success="imageUploadSuccess">
                <div class="form-group form-group-label">
                    <div class="dc-labelgroup">
                        <label for="file">
                            <span class="dc-btn">{{ trans('lang.select_files') }}</span>
                        </label>
                        <span>{{ trans('lang.drop_files') }}</span>
                    </div>
                </div>
            </vue-dropzone>
            <div class="dc-downloads-files" v-if="this.type == 'multiple_types'">
                <ul :class="this.id">
                </ul>
            </div>
            <div :class="this.id" class="la-upload-preview form-group form-group-label" v-else></div>
        </div>
    </div>
</template>

<script>
import Event from '../event.js'
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
export default {
    props: ['id', 'img_ref', 'url', 'name', 'maxFiles', 'type', 'image_width', 'image_height'],
    components: {
        vueDropzone: vue2Dropzone
    },
    data: function () {
        return {
        loading: false,
        is_show:true,
        options: {
            error: {
                position: 'center',
                timeout: 4000,
            },
        },
        file_type: this.type,
        dropzoneOptions: {
                url: this.getUrl(),
                maxFilesize: 2, // MB
                maxFiles: this.getMaxFiles(),
                previewTemplate: this.getImageUploadTemplate(),
                previewsContainer: '',
                paramName:this.getName(),
                acceptedFiles:'image/*',
                headers: {
                    'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
                },
                init: function() {
                    var myDropzone = this;
                    this.on("removedfile", function(file) {
                        var input_hidden_id = jQuery('#'+myDropzone.element.id).parents('.dc-settingscontent').find('input[type=hidden]').attr('id');
                        document.getElementById(input_hidden_id).value = '';
                    });
                },
                accept: function(file, done) {
                    var myDropzone = this;
                    file.acceptDimensions = done;
                    var width = 'height';
                    var height = 'width';
                    Event.$on('invalid-dimentation', (data) => {
                            if (data.width) {
                                width = data.width;
                            }
                            if (data.height) {
                                height = data.height
                            }
                            
                        })
                    file.rejectDimensions = function() { 
                        done("image size must be less than " + width + '*' + height); 
                    };
                }   
            },
        }
    },
    methods:{
        imageUploadSuccess: function () {
            this.loading = false
        },
        getImageUploadTemplate() { 
            var template ='';
            if(this.type && this.type =='multiple_types') {
                template = `
                    <li>
                        <div class="dc-files-content">
                            <img src="${APP_URL}/images/file-icon.png" alt="${window.lang.trans.img_desc}">
                            <div class="dc-filecontent dz-filename">
                                <span data-dz-name></span><em><div class="dz-size" data-dz-size></div></em>
                                <a class="lnr lnr-cross" href="javascript:;" data-dz-remove=""></a>
                            </div>
                        </div>
                    </li>
                `
            } else {
                template = `
                    <div class="dc-uploadingbox ">
                        <figure><img data-dz-thumbnail /></figure> 
                        <div class="dc-uploadingbar"><div class="dz-filename" data-dz-name></div> 
                        <span data-dz-size></span> 
                        <a class="lnr lnr-cross" href="javascript:;" data-dz-remove=""></a></div> 
                    </div>
                `
            }
        return template;
        },
        showError(message){
            return this.$toast.error(' ', message, this.options.error);
        },
        getUrl() {
            return this.url;
        },
        getName() {
            return this.name;
        },
        getPreviewerClass() {
            return this.id;
        },
        getAcceptedFiles(){
            // console.log(this.type);
            // if(this.type && this.type =='image') {
            //     return 'image/*';
            // } else {
            //     return '';
            // }
        },
        getMaxFiles() {
            if(this.maxFiles) {
                return this.maxFiles;
            } else {
                return 1;
            }
        },
        failed:function(file, message, xhr) {
            if (this.loading == true) {
                this.loading = false
            }
            if (file.type != this.$refs[this.img_ref].options.acceptedFiles) {
                if (message == 'You can not upload any more files.') {
                    message = 'you need to remove file before uploading new one.'
                } else if (message == '' || message == null) {
                    message = 'Uploading failed';
                }
                this.showError(message);
                this.$refs[this.img_ref].removeFile(file);
                var input_hidden_id = jQuery('#'+this.$refs[this.img_ref].id).parents('.dc-settingscontent').find('.dc-userform input[type=hidden]').attr('id');
                if (input_hidden_id) {
                    document.getElementById(input_hidden_id).value = '';
                }
            } else {
                this.showError('uploading failed');
            }
        },
        addedFile:function(file){
            this.loading = true
            if(this.type && this.type =='multiple_types' && this.maxFiles > 1) {
                var count = 0;
                var li_count = jQuery('#'+this.$refs[this.img_ref].id).parents('.dc-settingscontent').find('.downloads li').length;
                count = li_count -1;
                var hidden_count = jQuery('#'+this.$refs[this.img_ref].id).parents('.dc-settingscontent').find('.downloads li input:hidden').length;
                jQuery('#'+this.$refs[this.img_ref].id).parents('.dc-settingscontent').find('.downloads li:last-child').append('<input type="hidden" value="'+file.name+'" id="hidden-'+count+'" class="hidden-file" name="attachments['+[count]+']">');
                count++
            } else {
                var input_hidden_id = jQuery('#'+this.$refs[this.img_ref].id).parents('.dc-settingscontent').find('input[type=hidden]').attr('id');
                document.getElementById(input_hidden_id).value = file.name;
            }
            
        },
        validateDimentation:function(file) {
            if ((file.width > this.image_width || file.height > this.image_height)) {
                Event.$emit('invalid-dimentation', { width:this.image_width, height:this.image_height });
                file.rejectDimensions()
            }
            else {
                file.acceptDimensions();
            }
        },   
    },
    mounted: function () {

    },
}
</script>
