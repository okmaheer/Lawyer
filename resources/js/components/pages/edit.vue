<template>
  <div class="col-11 at-preview-wrap">
    <div class="at-dhb-main_content at-listing-holder">
      <draggable class="container list-group dragArea" animation: 0, :list="sections" ref="sortable_section" group="section" @end="sortData" @change="updateSection">
        <div
          class="list-group-item jb-edit-box"
          v-for="(element, index) in sections"
          :key="element.id"
          :id="'section'+index"
        >
          <a href="javascript:void(0);" class="jb-delparent" v-on:click="removeSection('section'+index, index, element.value)"><i class="ti-trash"></i></a>
          <div class="jb-sortable">
            <a href="javascript:void(0);" class="jb-sorting-icon"><i class="ti-fullscreen"></i></a>
            <a href="javascript:void(0);" class="jb-copy"><i class="ti-layers"></i></a>
          </div>
          <heading
            :element_id="element.id"
            :parent_index="index"
            :heading_section="form.meta.headings"
            @editData="editSection(element)"
            v-if="element.section =='heading'"
          />
          <editor
            :element_id="element.id"
            :parent_index="index"
            :editor_section="form.meta.editors"
            @editData="editSection(element)"
            v-else-if="element.section =='editor'"
          />
          <service-tab
            :element_id="element.id"
            :parent_index="index"
            :service_section="form.meta.service_tabs"
            :tabs="service_section_tabs"
            @editData="editSection(element)"
            v-else-if="element.section =='service_tab'"
          />
          <about
            :element_id="element.id"
            :parent_index="index"
            :aboutSection="form.meta.about_sections"
            @editData="editSection(element)"
            :pageID="page_id"
            v-else-if="element.section =='about_section'"
          />
          <how-work
            :element_id="element.id"
            :parent_index="index"
            :howWorkSection="form.meta.how_work_sections"
            :howWorkContent="howWorkSectionContent"
            @editData="editSection(element)"
            :pageID="page_id"
            v-else-if="element.section =='how_work_section'"
          />
          <speciality-section
            :element_id="element.id"
            :parent_index="index"
            :speciality_section="form.meta.speciality_sections"
            @editData="editSection(element)"
            v-else-if="element.section =='speciality_section'"
          />
          <app-section
            :element_id="element.id"
            :parent_index="index"
            :appSection="form.meta.app_sections"
            @editData="editSection(element)"
            :pageID="page_id"
            v-else-if="element.section =='app_section'"
          />
          <article-section
            :element_id="element.id"
            :parent_index="index"
            :article_section="form.meta.article_sections"
            :articles="articles"
            @editData="editSection(element)"
            v-else-if="element.section =='article_section'"
          />
          <sliderv1
            :element_id="element.id"
            :parent_index="index"
            :slider_section="form.meta.slidersFirstVersion"
            :pageID="page_id"
            :roles="searchableRoles"
            :selected_search_form_type="selected_search_form_type"
            @editData="editSection(element)"
            v-else-if="element.section =='sliderV1'"
          />
          <search
            :element_id="element.id"
            :parent_index="index"
            :searchForm="form.meta.search_forms"
            @editData="editSection(element)"
            :locations="sectionLocations"
            :roles="searchableRoles"
            :selected_search_form_type="selected_search_form_type"
            :pageID="page_id"
            v-else-if="element.section =='search_form'"
          />
          <two-column
            :element_id="element.id"
            :parent_index="index"
            :cloneElement="cloneElement"
            :two_column_section="form.meta.two_columns"
            @editData="editSection(element)"
            :pageID="page_id"
            v-else-if="element.section =='two_column'"
          />
        </div>
      </draggable>
    </div>
    <div id="amt-sidebar-pagebuilder" class="amt-sidebar-pagebuilder">
      <div id="amt-btnmenutoggle" v-on:click="addClass" class="amt-btnmenutoggle">
        <a href="javascript:void(0)" class="btn"><span class="ti-settings"></span></a>
      </div>
      <div class="at-verticalscrollbar">
        <form method="POST" id="pages" class="wt-formtheme wt-formprojectinfo wt-formcategory" @submit.prevent="submitPage()">
          <div class="amt-sidebar-section-wrap" id="amt-sidebar-section-wrap">
            <div class="amt-titlehead">
              <h3>{{ trans('lang.custom_page_builder') }}<em class="amt-tag">{{ trans('hot') }}</em></h3>
              <p>{{ trans('lang.custom_page_builder_note') }}</p>
            </div>
            <div class="amt-section-select">
              <input type="text" name="title" class="form-control" placeholder="Title" v-model="form.title">
            </div>
            <div class="amt-section-select">
              <tinymce-editor 
                v-model="form.body" 
                :init="{height: 350, plugins: 'paste link code advlist autolink lists link editor charmap print', toolbar1: 'undo redo code | bold italic underline strikethrough | fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist', menubar:false, statusbar: false, extended_valid_elements:'span[style],i[class]'}"
                v-if="form.body && form.body != 'null'"
              >
              </tinymce-editor>
            </div>
            <div class="amt-allsection">
              <span class="amt-allsection__title"><strong> {{ list.length }} {{ trans('lang.sections_avail') }} </strong></span>
              <div class="amt-sections">
                <draggable
                  tag="ul"
                  class="wt-draggable-group dragArea"
                  :list="list"
                  ref="sortable_section"
                  :clone="cloneSection"
                  :sort="false"
                  :group="{ name: 'section', pull: 'clone', put: false }">
                  <li v-for="(element, listIndex) in list" :key="element.name+listIndex">
                    <div class="amt-section-slot">
                      <img :src="IconPath+element.icon" alt="img description">
                      <span>{{ element.name }}</span>
                    </div>
                  </li>
                </draggable>
              </div>
            </div>
            <div class="amt-section-footer">
              <a href="javascript:void(0);"
                 class="at-collapse-switches"
                 data-toggle="collapse"
                 data-target="#at-collapse-switches1"
                 aria-expanded="false"
              >
                {{ trans('lang.headers') }}
                <i class="ti-angle-right"></i>
              </a>
              <div id="at-collapse-switches1" class="amt-switches-options collapse">
                <headers :form="form"/>
              </div>
              <!-- Header Styling -->
              <a href="javascript:void(0);"
                 class="at-collapse-switches"
                 data-toggle="collapse"
                 data-target="#at-collapse-switches-header"
                 aria-expanded="false"
                 v-on:click="displayHeaderStyling"
              >
                {{ trans('lang.header_styling') }}
                <i class="ti-angle-right"></i>
              </a>
              <div id="at-collapse-switches-header" class="amt-switches-options collapse">
                <header-styling :form="form" :pageID="form.id" :displayColorSettings="displayColorSettings"/>
              </div>
              <!-- Page Options -->
              <a href="javascript:void(0);"
                 class="at-collapse-switches"
                 data-toggle="collapse"
                 data-target="#at-collapse-switches"
                 aria-expanded="false"
              >
                {{ trans('lang.page_options') }}
                <i class="ti-angle-right"></i>
              </a>
              <div id="at-collapse-switches" class="amt-switches-options collapse">
                <page-data :form="form" :parentPages="parentPages" :pageID="form.id"/>
              </div>
            </div>
          </div>
          <div class="amt-section-content-area" id="amt-section-content-area" style="display:none;">
            <div class="amt-titlehead">
              <h3>
                <a href="javascript.void(0);" class="amt-section-back" v-on:click.prevent="displaySection"><i class="ti-angle-left"></i></a>
                {{ trans('lang.custom_page_builder') }}
                <em class="amt-tag">{{ trans('lang.hot') }}</em>
              </h3>
              <p>{{ trans('lang.custom_page_builder_note') }}</p>
            </div>
            <heading-form
              :headings="form.meta.headings[this.currentElementIndex]"
              v-if="currentSection =='heading'"
            />
            <editor-form
              :editor="form.meta.editors[this.currentElementIndex]"
              v-if="currentSection =='editor'"
            />
            <servicetab-form
              :service_tabs="form.meta.service_tabs[this.currentElementIndex]"
              v-if="currentSection =='service_tab'"
            />
           
            <work-form
              :howWork="form.meta.how_work_sections[this.currentElementIndex]"
              v-if="currentSection =='how_work_section'"
            />
            <speciality-form
              :speciality="form.meta.speciality_sections[this.currentElementIndex]"
              v-if="currentSection =='speciality_section'"
            />
            
            <article-form
              :article="form.meta.article_sections[this.currentElementIndex]"
              v-if="currentSection =='article_section'"
            />
            <sliderv1-form
              :slider="form.meta.slidersFirstVersion[this.currentElementIndex]"
              :sliders="sectionSliders"
              v-if="currentSection =='sliderV1'"
            />
            <search-form
              :search="form.meta.search_forms[this.currentElementIndex]"
              :currentElementID="currentElementID"
              :pageID="page_id"
              :cloneElement="cloneElement"
              v-if="currentSection =='search_form'"
            />
            <app-section-form
              :app="form.meta.app_sections[this.currentElementIndex]"
              :currentElementID="currentElementID"
              :pageID="page_id"
              :cloneElement="cloneElement"
              v-if="currentSection =='app_section'"
            />
            <about-form
              :about="form.meta.about_sections[this.currentElementIndex]"
              :pageID="page_id"
              :cloneElement="cloneElement"
              :currentElementID="currentElementID"
              v-if="currentSection =='about_section'"
            />
            <twocolumn-form
              :two_column="form.meta.two_columns[this.currentElementIndex]"
              :pageID="page_id"
              :currentElementIndex="currentElementIndex"
               :cloneElement="cloneElement"
               :currentElementID="currentElementID"
              v-if="currentSection =='two_column'"
            />
          </div>
          <div class="at-account-save__button">
            <button type="submit" class="dc-btn btn-success">
              {{ trans('lang.update') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
import Event from '../../event'
import draggable from "vuedraggable"
import PageData from './page-data'
import heading from './sections/heading'
import headingForm from './section-forms/heading'
import imageSection from './sections/image'
import imageForm from './section-forms/image'
import editor from './sections/editor'
import editorForm from './section-forms/editor'
import serviceTab from './sections/service'
import servicetabForm from './section-forms/service'
import about from './sections/about'
import aboutForm from './section-forms/about'
import howWork from './sections/how-work'
import workForm from './section-forms/how-work'
import specialitySection from './sections/speciality'
import specialityForm from './section-forms/speciality'
import appSection from './sections/app'
import appSectionForm from './section-forms/app'
import articleSection from './sections/article'
import articleForm from './section-forms/article'
import sliderv1Form from './section-forms/sliderV1'
import sliderv1 from './sections/sliders/index'
import search from './sections/search'
import searchForm from './section-forms/search'
import twoColumn from './sections/two_column'
import twocolumnForm from './section-forms/two_column'
import headers from './headers'
import Editor from '@tinymce/tinymce-vue'
import headerStyling from './header-styling'

let idGlobal = 1

export default {
  props:['section_list', 'layout_list', 'page_id', 'selected_parent', 'selected_search_form_type'],
  order: 5,
  components: {
    'tinymce-editor': Editor,
    headerStyling,
    headers,
    twocolumnForm,
    twoColumn,
    search,
    searchForm,
    sliderv1,
    sliderv1Form,
    articleSection,
    articleForm,
    draggable,
    heading,
    headingForm,
    imageSection,
    imageForm,
    editor,
    editorForm,
    PageData,
    serviceTab,
    servicetabForm,
    about,
    aboutForm,
    howWork,
    workForm,
    specialitySection,
    specialityForm,
    appSection,
    appSectionForm,
  },
  data () {
    return {
      displayColorSettings: true,
      form: {
        parent_type:'page',
        parent_id:'',
        pageMeta:{
          show_page: false,
          show_page_title: false,
          meta_title: '',
          seo_desc: '',
          sidebarOrder:'left',
          header:'headerv1',
          sidebar: false,
          headerStyling:{
            logo:'',
            menuColor:'#3d4461',
            menuHoverColor:'transparent',
            color:'#3d4461',
          },
        },
        slug: '',
        id: '',
        title: '',
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
      service_section_tabs: [],
      howWorkSectionContent: [],
      articles: [],
      currentElementIndex: '',
      currentSection: '',
      sectionSliders:[],
      sections: [],
      list: [],
      list:JSON.parse(this.section_list),
      layouts:JSON.parse(this.layout_list),
      baseURL:APP_URL,
      sectionLocations:[],
      searchableRoles:[],
      IconPath:APP_URL+'/images/page-builder/',
      parentPages:[],
      cloneElement:false
    }
  },
  created: function () {
    this.getPageData()
    this.getPages()
    this.getServiceTabs()
    this.getHowWorkContent()
    this.getArticles();
    this.getSliders()
    this.getLocations()
    this.getSearchableRoles()
    this.getParentPages()
    document.querySelector('body').classList.add('page-builder-body')
  },
  methods: {
    displayHeaderStyling: function() {
      var self = this
      self.displayColorSettings = false
      setTimeout (function () {
          self.displayColorSettings = true
          console.log(self.displayColorSettings)
      },5 )
    },
    async getSliders () {
      var self = this
      await axios.get(APP_URL +'/section/get-sliders/')
        .then(function (response) {
          self.sectionSliders = response.data.slides
        })
    },
    async getParentPages () {
      var self = this
      await axios.get(APP_URL +'/section/get-parent-pages/'+self.page_id)
        .then(function (response) {
          self.parentPages = response.data.parent
        })
    },
    async getLocations () {
      var self = this
      await axios.get(APP_URL +'/section/get-locations')
        .then(function (response) {
          self.sectionLocations = response.data.locations
        })
    },
    async getSearchableRoles () {
      var self = this
      await axios.get(APP_URL +'/section/get-roles')
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
    getPageData: function () {
      let id = this.page_id
      var self = this
      axios
        .get(APP_URL+ '/get-edit-page/' + id)
        .then(function (response) {
          if (response.data.type == 'success') {
            self.pageData = response.data.page
            self.form.id = self.pageData.id
            self.form.title = self.pageData.title
            self.form.body = self.pageData.body
            self.form.slug = self.pageData.slug
            self.form.parent_id = self.selected_parent
            self.form.parent_type = self.pageData.parent_type
            self.form.pageMeta.meta_title = self.pageData.page_options.meta_title
            self.form.pageMeta.meta_desc = self.pageData.page_options.seo_desc
            self.form.pageMeta.show_page_title = self.pageData.page_options.show_page_title
            self.form.pageMeta.show_page = self.pageData.page_options.show_page
            self.form.pageMeta.sidebar = self.pageData.page_options.sidebar
            self.form.pageMeta.headerStyling = self.pageData.page_options.headerStyling ? self.pageData.page_options.headerStyling : self.form.pageMeta.headerStyling
            self.form.pageMeta.header = (self.pageData.page_options.header) ? self.pageData.page_options.header :'headerv1'
            self.getSectionData()
          }
        })
        .catch(function (error) { })
    },
    getSectionData: function () {
      let id = this.page_id
      var self = this
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
            }
          }
        })
        .catch(function (error) { })
    },
    removeSection: function (elementId, index, section) {
      var selectedSectionIndex = this.getArrayIndex(this.form.meta[section], 'parentIndex', index)
      this.form.meta[section].splice(selectedSectionIndex, 1)
      this.sections.splice(index, 1)
      document.getElementById(elementId).remove()
      this.displaySection()
    },
    displaySection: function () {
      document.getElementById('amt-sidebar-section-wrap').style.display = 'block'
      document.getElementById('amt-section-content-area').style.display = 'none'
    },
    editSection: function (element) {
      this.currentSection = element.section
      this.currentElementIndex = this.getArrayIndex(this.form.meta[element.value], 'id', element.id)
      this.cloneElement = true
      this.currentElementID = element.id
      Event.$emit('section-edit')
      document.getElementById('amt-sidebar-section-wrap').style.display = 'none'
      document.getElementById('amt-section-content-area').style.display = 'block'
    },
    cloneSection: function (evt) {
      this.cloneElement = true
      if (this.sections.length == 1) {
        idGlobal = idGlobal + this.sections.length
      } else {
        idGlobal = idGlobal + (this.sections.length - 1)
      }
      var newID = this.newIdInArray(this.sections, idGlobal)
      if (newID == true) {
        idGlobal = idGlobal + 2
      }
      return {
        name: evt.name,
        section: evt.section,
        value: evt.value,
        icon: evt.icon,
        id: idGlobal++
      }
    },
    newIdInArray: function (arr, id) {
      const { length } = arr
      return arr.some(el => el.id === id)
    },
    addClass: function () {
      document.querySelector('.amt-sidebar-pagebuilder').classList.toggle('amt-pagebuilderon')
    },
    getPages (page = '') {
      
    },
    submitPage: function () {
      var self = this
      var l
      self.sections.map(function (item, index) {
        if (self.form.meta[item.value]) {
          var formIndex = self.getArrayIndex(self.form.meta[item.value], 'id', item.id)
          self.form.meta[item.value][formIndex].parentIndex = index
        }
      })
      self.form.sections = JSON.stringify(self.sections)
      axios.post(APP_URL +'/admin/update-page', self.form)
        .then(function (response) {
          if (response.data.type == 'success') {
            self.showMessage(self.trans('lang.page_updated'))
            window.location.replace(APP_URL + '/admin/pages');
          }
        })
        .catch(function (error) {
          if (error.response.data.errors.title) {
            self.showError(self.trans('lang.title_required'))
          }
        })
    },
    sortData: function (evt) {
      this.sections = this.sections.sort((a, b) => a.order - b.order)
    },
    updateSection: function (evt) {
      if (evt.added) {
        if (evt.added.element.section == 'heading') {
          var heading = {
            title: 'Add your Heading text here',
            link: '',
            url: '',
            tag: 'h1',
            action: '',
            alignment: '',
            color: '#000',
            padding: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            margin: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            elementClass: '',
            elementId: '',
            sectionId: '',
            sectionClass: '',
            id: this.sections[evt.added.newIndex].id,
            parentIndex: ''
          }
          this.form.meta.headings.push(heading)
        } else if (evt.added.element.section == 'image') {
          var image = {
            url: '',
            link: '',
            linkURL: '',
            action: '',
            alignment: '',
            background: '#fff',
            width: '100',
            height: '',
            widthUnit: '%',
            heightUnit: 'px',
            opacity: '',
            padding: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            margin: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            elementClass: '',
            elementId: '',
            sectionId: '',
            sectionClass: '',
            id: this.sections[evt.added.newIndex].id,
            parentIndex: ''
          }
          this.form.meta.images.push(image)
        } else if (evt.added.element.section == 'editor') {
          var editor = {
            content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ac augue ac velit congue interdum. Morbi nec nibh sem. Morbi tempor neque in pharetra laoreet. Phasellus feugiat, magna sed mattis placerat, dui lacus maximus massa, sit amet consectetur dolor tortor eu nisi. Nam justo mauris, pretium sit amet condimentum id, dictum sed tellus. Curabitur ac tempor tortor. Fusce consequat, ipsum a sollicitudin cursus, metus libero eleifend tortor, non finibus arcu ante ut nisl. Duis convallis tristique lacus vitae imperdiet. Sed aliquam in ex ac iaculis. Nunc at lacinia diam. In feugiat neque sed nibh suscipit, ut ultricies eros malesuada. Pellentesque ultrices mi quis arcu molestie, efficitur convallis ipsum interdum. Aliquam mauris tortor, imperdiet sit amet euismod at, elementum ac est. Fusce felis purus, aliquet et enim ut, feugiat viverra lectus. Suspendisse cursus nibh nec elit laoreet convallis. Nunc erat tortor, imperdiet ac cursus vel, commodo quis mi.',
            background: '#fff',
            color: '#000',
            padding: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            margin: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            elementClass: '',
            elementId: '',
            sectionId: '',
            sectionClass: '',
            id: this.sections[evt.added.newIndex].id,
            parentIndex: ''
          }
          this.form.meta.editors.push(editor)
        } else if (evt.added.element.section == 'service_tab') {
          var service = {
            padding: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            margin: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            elementClass: '',
            elementId: '',
            sectionId: '',
            sectionClass: '',
            id: this.sections[evt.added.newIndex].id,
            parentIndex: ''
          }
          this.form.meta.service_tabs.push(service)
        } else if (evt.added.element.section == 'about_section') {
          var about = {
            title:'Home With One Click',
            titleColor:'#3d4461',
            subtitle:'Bring Care To Your',
            subtitleColor:'#ff5851',
            description:'<p>Lorem ipsum dolor amet consectetur adipisicing eliteiuim sete eiusmod tempor incididunt ut labore etnalom dolore magna aliqua.</p>',
            btntitle1:'About us',
            btnurl1:'#',
            btntitle2:'contact',
            btnurl2:'#',
            afterSection:'',
            image:{
              title:'Greetings & Welcome',
              titleColor:'#fff',
              subtitle:'Dr. Tyrone Grindle',
              subtitleColor:'#fff',
              captionBackground:'#3d4461',
              url:'',
              width: '100',
              height: '',
              widthUnit: '%',
              heightUnit: 'px',
              opacity: '',
              after:'#ff5851'
            },
            padding: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            margin: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            background:'#fff',
            sectionId: '',
            sectionClass: '',
            id: this.sections[evt.added.newIndex].id,
            parentIndex: ''
          }
          this.form.meta.about_sections.push(about)
        } else if (evt.added.element.section == 'how_work_section') {
          var howWork = {
            titleColor:'#3d4461',
            subtitleColor:'#3d4461',
            contentBackground:'#e8f6ff',
            contentColor:'#3d4461',
            tabBackground:'#fff',
            padding: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            margin: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            sectionId: '',
            sectionClass: '',
            id: this.sections[evt.added.newIndex].id,
            parentIndex: ''
          }
          this.form.meta.how_work_sections.push(howWork)
        } else if (evt.added.element.section == 'speciality_section') {
          var speciality = {
            title:'Our Top Rated',
            titleColor:'#3d4461',
            subtitleColor:'#3d4461',
            contentBackground:'#e8f6ff',
            contentColor:'#3d4461',
            specialityID:'',
            detail:[],
            padding: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            margin: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            sectionId: '',
            sectionClass: '',
            id: this.sections[evt.added.newIndex].id,
            parentIndex: ''
          }
          this.form.meta.speciality_sections.push(speciality)
        } else if (evt.added.element.section == 'app_section') {
          var app = {
            title:'Care On The GO',
            subtitle:'Download Mobile App',
            description:'<p>Lorem ipsum dolor amet consectetur adipisicing eliteiuim sete eiusmod tempor incididunt ut labore etnalom dolore magna aliqua.</p>',
            titleColor:'#3d4461',
            subtitleColor:'#3d4461',
            background:'#e8f6ff',
            googlePlay:{
              image:'',
              url:'#',
            },
            appStore:{
              image:'',
              url:'#',
            },
            content:{
              color:'#3d4461',
              background:false,
              backgroundColor:'#3d4461',
              padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0,
                unit: 'px'
              },
              margin: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0,
                unit: 'px'
              },
            },
            image:{
              url:'',
              width: '100',
              widthUnit: '%',
              opacity: '',
            },
            padding: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            margin: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            sectionId: '',
            sectionClass: '',
            id: this.sections[evt.added.newIndex].id,
            parentIndex: ''
          }
          this.form.meta.app_sections.push(app)
        } else if (evt.added.element.section == 'article_section') {
          var article = {
            title:'Latest Articles',
            subtitle:'Read Professional Articles',
            description:'<p>Lorem ipsum dolor amet consectetur adipisicing eliteiuim sete eiusmod tempor incididunt ut labore etnalom dolore magna aliqua udiminimate veniam quis norud.</p>',
            titleColor:'#3d4461',
            subtitleColor:'#3d4461',
            contentColor:'#3d4461',
            background:'#e8f6ff',
            padding: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            margin: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            elementClass: '',
            elementId: '',
            sectionId: '',
            sectionClass: '',
            id: this.sections[evt.added.newIndex].id,
            parentIndex: ''
          }
          this.form.meta.article_sections.push(article)
        } else if (evt.added.element.section == 'sliderV1') {
          var slider = {
            slider_id: '',
            id: this.sections[evt.added.newIndex].id,
            parentIndex: ''
          }
          this.form.meta.slidersFirstVersion.push(slider)
        } else if (evt.added.element.section == 'search_form') {
          var search = {
            title:'Start Your Search',
            image:'',
            bannerSubheading:'Are You A Lawyer?',
            bannerHeading:'Join Our Team',
            bannerButton:'Join As Lawyer',
            bannerUrl:'#',
            bannerSubheadingColor:'#fff',
            bannerHeadingColor:'#fff',
            bannerBackground:'#3d4461',
            padding: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            margin: {
              top: -114,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            elementClass: '',
            elementId: '',
            sectionId: '',
            sectionClass: '',
            id: this.sections[evt.added.newIndex].id,
            parentIndex: ''
          }
          this.form.meta.search_forms.push(search)
        } else if (evt.added.element.section == 'two_column') {
          var two_column = {
            title:'To Get Your Solution',
            subtitle:'Ask Query To Qualifed Lawyers.',
            description:'<p>Lorem ipsum dolor amet consectetur adipisicing elit eiuim sete eiu tempor incididunt ut labore etnaloms dolore magna aliqua udiminimate veniam</p>',
            url:'#',
            btn_text:'Start Search',
            image:'',
            imageOrder:'right',
            titleColor:'#3d4461',
            subtitleColor:'#3d4461',
            contentColor:'#3d4461',
            imageWidth: '100',
            imageWidthUnit: '%',
            imageOpacity: '',
            contentSectionClass:'',
            contentSectionID:'',
            imageSectionClass:'',
            imageSectionID:'',
            row:{
              padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0,
                unit: 'px'
              },
              margin: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0,
                unit: 'px'
              },
              border:'1',
              borderWidth:'1',
              borderColor:'#eee',
            },
            padding: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            margin: {
              top: 0,
              right: 0,
              bottom: 0,
              left: 0,
              unit: 'px'
            },
            sectionBackground: 'transparent',
            sectionId: '',
            sectionClass: '',
            id: this.sections[evt.added.newIndex].id,
            parentIndex: ''
          }
          this.form.meta.two_columns.push(two_column)
        }
        this.editSection(evt.added.element)
      } else if (evt.moved) {
        var self = this
        setTimeout(function () {
          if (evt.moved.section == 'heading') {
            Event.$emit('heading-section-update')
          } else if (evt.moved.section == 'image') {
            Event.$emit('image-section-update')
          } else if (evt.moved.section == 'editor') {
            Event.$emit('editor-section-update')
          } else if (evt.moved.section == 'about_section') {
            Event.$emit('about-section-update')
          } else if (evt.moved.section == 'app_section') {
            Event.$emit('app-section-update')
          } else if (evt.moved.section == 'article_section') {
            Event.$emit('article-section-update')
          } else if (evt.moved.section == 'how_work_section') {
            Event.$emit('howWork-section-update')
          } else if (evt.moved.section == 'search_form') {
            Event.$emit('search-section-update')
          } else if (evt.moved.section == 'service_tab') {
            Event.$emit('service-section-update')
          } else if (evt.moved.section == 'sliderV1') {
            Event.$emit('slider-section-update')
          } else if (evt.moved.section == 'speciality_section') {
            Event.$emit('speciality-section-update')
          } else if (evt.moved.section == 'speciality_section') {
            Event.$emit('twoColumn-section-update')
          }
          self.editSection(evt.moved.element)
        }, 100)

      }
    }
  }
}
</script>
<style>
.flip-list-move {
  transition: transform 0.5s;
}
.no-move {
  transition: transform 0s;
}
</style>
