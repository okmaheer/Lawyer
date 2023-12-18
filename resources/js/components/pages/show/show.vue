<template>
  <div>
    <div v-for="(skeleton, index) in skeletons" :key="skeleton+index">
      <slider-skeleton v-if="sliderSkeleton && (skeleton == 'sliderV1' || skeleton == 'sliderV2')"/>
      <services-skeleton v-if="serviceSkeleton && skeleton == 'service_tab'"/>
      <div class="dc-main-section" v-if="aboutSkeleton && skeleton == 'about_section'">
        <div class="container" >
              <about-skeleton />
        </div>
      </div>
      <div class="dc-main-section" v-if="howWorkSkeleton && skeleton == 'how_work_section'">
        <div class="container">
          <how-work-skeleton />
        </div>
      </div>
      <div class="dc-main-section" v-if="specialitySkeleton && skeleton == 'speciality_section'">
        <div class="container-fluid" >
          <speciality-skeleton />
        </div>
      </div>
      <div class="dc-main-section" v-if="appSkeleton && skeleton == 'app_section'">
        <div class="container">
          <app-skeleton />
        </div>
      </div>
      <div class="dc-main-section" v-if="articleSkeleton && skeleton == 'article_section'">
        <div class="container" >
          <article-skeleton />
        </div>
      </div>
      <div class="dc-main-section" v-if="headingSkeleton && skeleton == 'heading'">
        <div class="container">
          <heading-skeleton />
        </div>
      </div>
      <div class="dc-main-section" v-if="introSkeleton && skeleton == 'two_column'">
        <div class="container">
          <intro-skeleton />
        </div>
      </div>
    </div>
      
    <div
      v-for="(element, index) in sections"
      :key="'section'+element.id+index"
      :class="'section-main-wrapper'+element.id+index"
    >
        <heading
          :element_id="element.id"
          :parent_index="index"
          :heading_section="form.meta.headings"
          v-if="element.section =='heading'"
        />
       <article-section
          :element_id="element.id"
          :parent_index="index"
          :article_section="form.meta.article_sections"
          :articles="articles"
          v-else-if="element.section =='article_section'"
       /> 
       <service-tab
          :element_id="element.id"
          :parent_index="index"
          :service_section="form.meta.service_tabs"
          :tabs="service_section_tabs"
          v-else-if="element.section =='service_tab'"
        />
        <about
          :element_id="element.id"
          :parent_index="index"
          :aboutSection="form.meta.about_sections"
          :pageID="page_id"
          v-else-if="element.section =='about_section'"
        />
        <how-work
          :element_id="element.id"
          :parent_index="index"
          :howWorkSection="form.meta.how_work_sections"
          :howWorkContent="howWorkSectionContent"
          v-else-if="element.section =='how_work_section'"
        />
        <speciality-section
          :element_id="element.id"
          :parent_index="index"
          :speciality_section="form.meta.speciality_sections"
          v-else-if="element.section =='speciality_section'"
        />
        <app-section
          :element_id="element.id"
          :parent_index="index"
          :appSection="form.meta.app_sections"
          :pageID="page_id"
          v-else-if="element.section =='app_section'"
        />
        <sliderv1
          :element_id="element.id"
          :parent_index="index"
          :slider_section="form.meta.slidersFirstVersion"
          :pageID="page_id"
          :roles="searchableRoles"
          :selected_search_form_type="selected_search_form_type"
          v-else-if="element.section =='sliderV1'"
        />
        <search
          :element_id="element.id"
          :parent_index="index"
          :searchForm="form.meta.search_forms"
          :roles="searchableRoles"
          :pageID="page_id"
          :selected_search_form_type="selected_search_form_type"
          v-else-if="element.section =='search_form'"
        />
        <editor
          :element_id="element.id"
          :parent_index="index"
          :editor_section="form.meta.editors"
          v-else-if="element.section =='editor'"
        />
        <intro
          :element_id="element.id"
          :parent_index="index"
          :pageID="page_id"
          :two_column_section="form.meta.two_columns"
          v-else-if="element.section =='two_column'"
        />
    </div>
  </div>
</template>
<script>
import heading from './sections/heading'
import editor from './sections/editor'
import serviceTab from './sections/service'
import about from './sections/about'
import howWork from './sections/how-work'
import specialitySection from './sections/speciality'
import appSection from './sections/app'
import articleSection from './sections/article'
import sliderv1 from './sections/sliders/index'
import search from './sections/search'
import intro from './sections/two_column'
import sliderSkeleton from './skeleton/slider'
import servicesSkeleton from './skeleton/services'
import aboutSkeleton from './skeleton/about'
import howWorkSkeleton from './skeleton/how-work'
import specialitySkeleton from './skeleton/speciality'
import appSkeleton from './skeleton/app'
import articleSkeleton from './skeleton/article'
import headingSkeleton from './skeleton/heading'
import introSkeleton from './skeleton/intro'

export default {
    props:['page_id', 'selected_search_form_type'],
    components: {
      introSkeleton,
      headingSkeleton,
      articleSkeleton,
      appSkeleton,
      specialitySkeleton,
      howWorkSkeleton,
      servicesSkeleton,
      sliderSkeleton,
      aboutSkeleton,
      heading,
      editor,
      about,
      serviceTab,
      howWork,
      specialitySection,
      appSection,
      articleSection,
      sliderv1,
      search,
      intro
    },
    data () {
      return {
        skeletons:[],
        sliderSkeleton: false,
        headingSkeleton: false,
        editorSkeleton: false,
        serviceSkeleton: false,
        aboutSkeleton: false,
        howWorkSkeleton: false,
        specialitySkeleton: false,
        appSkeleton: false,
        articleSkeleton: false,
        searchSkeleton: false,
        introSkeleton: false,
        form: {
            slug: '',
            id: '',
            show_page_title: false,
            show_page_banner: false,
            title: '',
            meta_title: '',
            meta_desc: '',
            banner: '',
            sections: [],
            meta: {
              headings: [],
              images: [],
              editors: [],
              service_tabs:[],
              about_sections:[],
              how_work_sections:[],
              speciality_sections:[],
              app_sections:[],
              article_sections:[],
              slidersFirstVersion:[],
              search_forms:[],
              two_columns:[],
              parent_id: null
            }
          },
          loading: true,
          service_section_tabs: [],
          howWorkSectionContent: [],
          articles: [],
          currentElementIndex: '',
          currentSection: '',
          sections: [],
          list: [],
          searchableRoles: [],
          baseURL:APP_URL,
          IconPath:APP_URL+'/images/page-builder/',
        }
    },
  created () {
    this.getPageData()
    this.getServiceTabs()
    this.getHowWorkContent()
    this.getArticles()
    this.getSearchableRoles()
  },
  methods: {
    async getSearchableRoles () {
      var self = this
      await axios.get(APP_URL + '/section/get-roles')
        .then(function (response) {
          self.searchableRoles = response.data.roles
        })
    },
    getServiceTabs() {
        var self = this;
        axios.get(APP_URL + '/section/get-services')
        .then(function (response) {
            if (response.data.type === 'success') {
                self.service_section_tabs = response.data.services
            } 
        })
        .catch(function (error) {

        })
    },
    getHowWorkContent() {
        var self = this;
        axios.get(APP_URL + '/section/get-howwork-content')
        .then(function (response) {
            if (response.data.type === 'success') {
                self.howWorkSectionContent = response.data.howWork
            } 
        })
        .catch(function (error) {

        })
    },
    getArticles() {
        var self = this;
        axios.get(APP_URL + '/section/get-articles')
        .then(function (response) {
            if (response.data.type === 'success') {
              self.articles = response.data.articles
            } 
        })
        .catch(function (error) {

        })
    },
    async getPageData () {
      var url = ''
      let id = this.page_id
      url = APP_URL + '/get-edit-page/' + id
      var self = this
      await axios
        .get(url)
        .then(function (response) {
          if (response.data.type == 'success') {
            self.pageData = response.data.page
            if (self.pageData.section_list) {
                self.pageData.section_list.forEach(element => {
                  self.skeletons.push(element.section)
                  if (element.section == 'sliderV1') {
                    self.sliderSkeleton = true 
                  } else if (element.section == 'service_tab') {
                    self.serviceSkeleton = true
                  } else if (element.section == 'about_section') {
                    self.aboutSkeleton = true
                  } else if (element.section == 'how_work_section') {
                    self.howWorkSkeleton = true
                  } else if (element.section == 'speciality_section') {
                    self.specialitySkeleton = true
                  } else if (element.section == 'app_section') {
                    self.appSkeleton = true
                  } else if (element.section == 'article_section') {
                    self.articleSkeleton = true
                  } else if (element.section == 'heading') {
                    self.headingSkeleton = true
                  } else if (element.section == 'two_column') {
                    self.introSkeleton = true
                  }
                });
            }
            self.form.id = self.pageData.id
            self.form.title = self.pageData.title
            self.form.meta_title = self.pageData.meta_title
            self.form.meta_desc = self.pageData.meta_desc
            self.form.banner = self.pageData.banner
            self.form.show_page_title = self.pageData.show_page_title
            self.form.show_page_banner = self.pageData.show_page_banner
            jQuery(".preloader-outer").fadeOut();
            self.getSectionData()
          }
        })
        .catch(function (error) { })
    },
    getSectionData: function () {
      var self = this
      let id = this.page_id
      axios
        .get(APP_URL + '/page/get-sections/' + id)
        .then(function (response) {
          if (response.data.type == 'success') {
            self.pageData.sections = response.data.section_data
            if (self.pageData.section_list) {
              self.sections = self.pageData.section_list
              var sectionArray = Object.keys(self.pageData.sections).map(i => self.pageData.sections[i])
              sectionArray.forEach(element => {
                element = element.filter(function (sec) {
                  self.sections.find(function (e) {
                    if (typeof sec != 'string') {
                      if (e.id == sec.id) {
                        self.form.meta[e.value].push(sec)
                      }
                    }
                  })
                })
              })
              setTimeout(function() { 
                self.sliderSkeleton = false
                self.serviceSkeleton = false
                self.headingSkeleton = false
                self.aboutSkeleton = false
                self.howWorkSkeleton = false
                self.specialitySkeleton = false
                self.appSkeleton = false
                self.articleSkeleton = false
                self.introSkeleton = false
              }, 2000);
            }
          }
        })
        .catch(function (error) {})
    }
  }
}
</script>
