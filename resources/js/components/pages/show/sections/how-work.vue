<template>
    <section 
        :class="howWork.sectionClass + ' dc-haslayout'"
        :id="howWork.sectionId" 
        :style="sectionStyle" 
        v-if="Object.entries(howWork).length != 0"
    >
        <div class="dc-haslayout dc-bgcolor dc-main-section dc-workholder" :style="contentSectionStyle">
            <div class="container">
                <div class="row justify-content-center align-self-center">
                    <div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-8 push-lg-2">
                        <div class="dc-sectionhead dc-text-center">
                            <div class="dc-sectiontitle">
                                <h2 :style="{color:howWork.titleColor}"><span :style="{color:howWork.subtitleColor}">{{ howWorkContent.content.subtitle }}</span>{{ howWorkContent.content.title }}</h2>
                            </div>
                            <div class="dc-description" v-html="howWorkContent.content.hw_desc"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dc-haslayout dc-main-section dc-workdetails-holder" :style="{background:howWork.tabBackground}">
            <div class="container">
                <div class="row" v-if="howWorkContent.tabs">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4" v-for="(tab, index) in howWorkContent.tabs" :key="index">
                        <div class="dc-workdetails">
                            <div class="dc-workdetail">
                                <figure>
                                    <img  :src="baseUrl+'/uploads/settings/home/'+tab.tab_img"  alt="img-sesc">
                                </figure>
                            </div>
                            <div class="dc-title">
                                <span>{{ tab.subtitle }}</span>
                                <h3>{{ tab.title }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
export default {
    props:['parent_index', 'element_id', 'howWorkSection', 'pageID', 'howWorkContent'],
    data() {
        return {
            howWork:{},
            isActive:false,
            baseUrl: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
        }
    },
    computed: {
        sectionStyle() {
            return {
                padding: `${this.howWork.padding.top}${this.howWork.padding.unit} ${this.howWork.padding.right}${this.howWork.padding.unit} ${this.howWork.padding.bottom}${this.howWork.padding.unit} ${this.howWork.padding.left}${this.howWork.padding.unit}`,
                margin: `${this.howWork.margin.top}${this.howWork.margin.unit} ${this.howWork.margin.right}${this.howWork.margin.unit} ${this.howWork.margin.bottom}${this.howWork.margin.unit} ${this.howWork.margin.left}${this.howWork.margin.unit}`,
                'text-align': this.howWork.alignment,
                background: this.howWork.background
            }
        },
        contentSectionStyle() {
            return {
                background:this.howWork.contentBackground,
                color:this.howWork.contentColor
            }
        },
    },
    updated: function() {
        var index = this.getArrayIndex(this.howWorkSection, 'id', this.element_id)
        if (this.howWork[index]) {
            this.howWork = this.howWork[index]
        }
        this.howWork.id = this.element_id
    },
    mounted: function() {
        this.isActive = false
        var self= this
        Event.$on('howWork-section-update', (data) => {
            setTimeout(function(){ 
                self.isActive = !self.isActive;
            }, 10);
        })
    },
    created: function() {
        var self = this
        setTimeout(function(){ 
            var index = self.getArrayIndex(self.howWorkSection, 'id', self.element_id)
            if (self.howWorkSection[index]) {
                self.howWork = self.howWorkSection[index]
            }
        }, 100);
    },
};
</script>
