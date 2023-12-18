<template>
    <section 
        :class="two_column.sectionClass + ' dc-haslayout dc-main-section'" 
        :id="two_column.sectionId" 
        :style="sectionStyle" 
        v-if="Object.entries(two_column).length != 0"
    >
        <div class="container">
            <div class="dc-itsworkvtwoitem" :style="rowStyle" >
                <div class="row align-items-center">
                    <div class="col-12 col-md-12 col-lg-6">
                        <div 
                            :class="[two_column.contentSectionClass,'dc-workvtwocontent']" 
                            :style="{color:two_column.contentColor}"
                            :id="two_column.contentSectionID"
                        >
                            <div class="dc-title">
                                <h3 :style="{color:two_column.titleColor}">
                                    <span :style="{color:two_column.subtitleColor}">{{two_column.subtitle}}</span> 
                                    {{two_column.title}}
                                </h3>
                            </div>
                            <div class="dc-description" v-html="two_column.description">
                            </div>
                            <div class="dc-btnarea">
                                <a :href="two_column.url" class="dc-btn">{{two_column.btn_text}}</a>
                            </div>
                        </div>												
                    </div>
                    <div :class="[two_column.imageOrder == 'left' ? 'order-first' : 'order-last', 'col-12 col-md-12 col-lg-6']" v-if="two_column.image">
                        <div :class="[two_column.imageSectionClass,'dc-workvtwoimg']" :id="two_column.imageSectionID">
                            <figure class="dc-doccareimg">
                                <img 
                                    :class="[two_column.imageOrder == 'left' ? 'float-left' : 'float-right']"
                                    :src="baseUrl+'/uploads/pages/'+pageID+'/'+two_column.image" 
                                    alt="img description" 
                                    v-bind:style="ImageStyle"
                                    v-if="pageID"
                                />
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
export default {
    props:['parent_index', 'element_id', 'two_column_section', 'pageID'],
    data() {
        return {
            two_column:{},
            isActive:false,
            baseUrl: APP_URL,
            tempUrl:APP_URL+'/uploads/pages/temp/',
        }
    },
    computed: {
        sectionStyle() {
            return {
                padding: `${this.two_column.padding.top}${this.two_column.padding.unit} ${this.two_column.padding.right}${this.two_column.padding.unit} ${this.two_column.padding.bottom}${this.two_column.padding.unit} ${this.two_column.padding.left}${this.two_column.padding.unit}`,
                margin: `${this.two_column.margin.top}${this.two_column.margin.unit} ${this.two_column.margin.right}${this.two_column.margin.unit} ${this.two_column.margin.bottom}${this.two_column.margin.unit} ${this.two_column.margin.left}${this.two_column.margin.unit}`,
                'text-align': this.two_column.alignment,
                background: this.two_column.sectionBackground
            }
        },
        ImageStyle() {
            return {
                width: this.two_column.imageWidth+this.two_column.imageWidthUnit,
                opacity: this.two_column.imageOpacity,
            }            
        },
        rowStyle() {
            return {
                'border-bottom': this.two_column.row.border ? this.two_column.row.borderWidth+'px solid '+this.two_column.row.borderColor : 0,
                padding: `${this.two_column.row.padding.top}${this.two_column.row.padding.unit} ${this.two_column.row.padding.right}${this.two_column.row.padding.unit} ${this.two_column.row.padding.bottom}${this.two_column.row.padding.unit} ${this.two_column.row.padding.left}${this.two_column.row.padding.unit}`,
                margin: `${this.two_column.row.margin.top}${this.two_column.row.margin.unit} ${this.two_column.row.margin.right}${this.two_column.row.margin.unit} ${this.two_column.row.margin.bottom}${this.two_column.row.margin.unit} ${this.two_column.row.margin.left}${this.two_column.row.margin.unit}`,
            }            
        },
    },
    updated: function() {
        var index = this.getArrayIndex(this.two_column_section, 'id', this.element_id)
        if (this.two_column[index]) {
            this.two_column = this.two_column[index]
        }
        this.two_column.id = this.element_id
    },
    mounted: function() {},
    methods:{
        
    },
    created: function() {
        var self = this
        setTimeout(function(){ 
            var index = self.getArrayIndex(self.two_column_section, 'id', self.element_id)
            if (self.two_column_section[index]) {
                self.two_column = self.two_column_section[index]
            }
        }, 100);
    },
};
</script>
