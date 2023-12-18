<template>
    <div class="element-preview-wrapper" v-on:click="editElement" >
        <section 
            :class="article.sectionClass + ' dc-haslayout dc-main-section'"
            :id="article.sectionId" 
            :style="sectionStyle" 
            v-if="Object.entries(article).length != 0"
        >
            <div class="container">
                <div class="row justify-content-center align-self-center">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 push-lg-2">
                        <div class="dc-sectionhead dc-text-center" :style="{color:article.contentColor}">
                            <div class="dc-sectiontitle">
                                <h2 :style="{color:article.titleColor}">
                                    <span :style="{color:article.subtitleColor}">{{ article.subtitle }}</span>
                                    {{ article.title }}
                                </h2>
                            </div>
                            <div class="dc-description" v-html="article.description"></div>
                        </div>
                    </div>
                    <div class="dc-articlesholder" v-if="articles">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 float-left" v-for="(item, index) in articles" :key="index">
                            <div class="dc-article">
                                <figure class="dc-articleimg">
                                    <img :src="item.article.image" alt="img_desc">
                                    <figcaption>
                                        <div class="dc-articlesdocinfo">
                                            <img :src="item.author.image" alt="img_desc">
                                            <span>{{ item.author.name }}</span>
                                        </div>
                                    </figcaption>
                                </figure>
                                <div class="dc-articlecontent">
                                    <div class="dc-title">
                                        <div class="dc-articleby-holder" v-if="item.article.categories">
                                            <a 
                                                class="dc-articleby"
                                                v-for="(category, index) in item.article.categories" :key="index"
                                                :href="category.url" 
                                            >
                                                {{ category.title }}
                                            </a>
                                        </div>
                                        <h3><a :href="item.article.url">{{ item.article.title }}</a></h3>
                                        <span class="dc-datetime">
                                            <i class="ti-calendar"></i> 
                                            {{ item.article.date }}
                                        </span>
                                    </div>
                                    <ul class="dc-moreoptions">
                                        <li>
                                            <a href="javascript:void(0);"><i class="ti-heart"></i></a> 
                                            {{ item.article.likes }} {{ trans('lang.likes') }}
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);"><i class="ti-eye"></i></a>
                                            {{ item.article.views }} {{ trans('lang.views') }}
                                        </li>
                                        <li :id="'dc-share-'+item.article.id" @click="socialPopup(item.article.id)" class="la-shareicon">
                                            <a href="javascript:void(0);"><i class="ti-share"></i> {{ trans('lang.share') }}</a>
                                            <ul class="dc-simplesocialicons dc-socialiconsborder">
                                                <li class="dc-facebook">
                                                    <a href="javascript:void()" @click="socialShare(item.article.share.facebook)" class="social-share">
                                                        <i class="fab fa-facebook-f"></i>
                                                    </a>
                                                </li>
                                                <li class="dc-twitter">
                                                    <a href="javascript:void()" @click="socialShare(item.article.share.twitter)" class="social-share">
                                                        <i class="fab fa-twitter"></i>
                                                    </a>
                                                </li>
                                                <li class="dc-linkedin">
                                                    <a href="javascript:void()" @click="socialShare(item.article.share.linkdin)" class="social-share">
                                                        <i class="fab fa-linkedin-in"></i></a>
                                                </li>
                                                <li class="dc-googleplus">
                                                    <a href="javascript:void()" @click="socialShare(item.article.share.googleplus)" class="social-share">
                                                        <i class="fab fa-google-plus-g"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import Event from '../../../event'
export default {
    props:['parent_index', 'element_id', 'article_section', 'articles'],
    data() {
        return {
            article:{},
            isActive:false,
            temImg:APP_URL+'/images/default-article.png'
        }
    },
    computed: {
        sectionStyle() {
            return {
                padding: `${this.article.padding.top}${this.article.padding.unit} ${this.article.padding.right}${this.article.padding.unit} ${this.article.padding.bottom}${this.article.padding.unit} ${this.article.padding.left}${this.article.padding.unit}`,
                margin: `${this.article.margin.top}${this.article.margin.unit} ${this.article.margin.right}${this.article.margin.unit} ${this.article.margin.bottom}${this.article.margin.unit} ${this.article.margin.left}${this.article.margin.unit}`,
                'text-align': this.article.alignment,
                background: this.article.background
            }
        },
    },
    updated: function() {
        var index = this.getArrayIndex(this.article_section, 'id', this.element_id)
        if (this.article_section[index]) {
            this.article = this.article_section[index]
        }
        this.article.id = this.element_id
    },
    mounted: function() {
        this.isActive = false
        var self= this
        Event.$on('article-section-update', (data) => {
            setTimeout(function(){ 
                self.isActive = !self.isActive;
            }, 10);
        })
    },
    methods:{
        socialPopup: function (id) {
            jQuery('#dc-share-' + id + ' ul').slideToggle('100');
        },
        socialShare: function (url) {
            event.preventDefault();
            var popupMeta = {
                width: 400,
                height: 400
            }

            var vPosition = Math.floor(($(window).width() - popupMeta.width) / 2),
                hPosition = Math.floor(($(window).height() - popupMeta.height) / 2);

            var popup = window.open(url, 'Social Share',
                'width=' + popupMeta.width + ',height=' + popupMeta.height +
                ',left=' + vPosition + ',top=' + hPosition +
                ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

            if (popup) {
                popup.focus();
                return false;
            }

        },
        add_wishlist: function (element_id, id, column) {
            var self = this;
            axios.post(APP_URL + '/user/add-wishlist', {
                id: id,
                column: column,
            })
                .then(function (response) {
                    if (response.data.authentication == true) {
                        if (response.data.type == 'success') {
                            jQuery('#' + element_id).addClass('wt-btndisbaled');
                            jQuery('#' + element_id).addClass('wt-clicksave');
                            jQuery('#' + element_id).addClass('dc-clicksave dc-btndisbaled');
                            self.showMessage(response.data.message);
                        } else {
                            self.showError(response.data.message);
                        }
                    } else {
                        self.showError(response.data.message);
                    }
                })
                .catch(function (error) {
                });
        },
        editElement: function() {
            var self = this
            this.$emit("editData");
        },
    },
    created: function() {
        var self = this
        setTimeout(function(){ 
            var index = self.getArrayIndex(self.article_section, 'id', self.element_id)
            if (self.article_section[index]) {
                self.article = self.article_section[index]
            }
            self.article.id = self.element_id
        }, 100);
    },
};
</script>
