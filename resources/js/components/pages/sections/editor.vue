<template>
    <div class="element-preview-wrapper" v-on:click="editElement">
        <section class="editor-section-wrapper" :class="editor.sectionClass" :id="editor.sectionId" v-bind:style="Style" v-if="Object.entries(editor).length != 0">
            <div class="container">
                <div class="at-description" v-html="editor.content" :class="editor.elementClass" :id="editor.elementId" v-if="editor.content">
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import Event from '../../../event'
export default {
    props:['parent_index', 'element_id', 'editor_section'],
    data() {
        return {
            editor:{},
            isActive:false
        }
    },
    computed: {
        Style() {
            return {
                padding: `${this.editor.padding.top}${this.editor.padding.unit} ${this.editor.padding.right}${this.editor.padding.unit} ${this.editor.padding.bottom}${this.editor.padding.unit} ${this.editor.padding.left}${this.editor.padding.unit}`,
                margin: `${this.editor.margin.top}${this.editor.margin.unit} ${this.editor.margin.right}${this.editor.margin.unit} ${this.editor.margin.bottom}${this.editor.margin.unit} ${this.editor.margin.left}${this.editor.margin.unit}`,
                color : this.editor.color,
                background : this.editor.background,
            }
        },
    },
    updated: function() {
        var index = this.getArrayIndex(this.editor_section, 'id', this.element_id)
        if (this.editor_section[index]) {
            this.editor = this.editor_section[index]
        }
        this.editor.id = this.element_id
    },
    mounted: function() {
        this.isActive = false
        var self= this
        Event.$on('editor-section-update', (data) => {
            setTimeout(function(){ 
                self.isActive = !self.isActive;
            }, 10);
        })
    },
    methods:{
        editElement: function() {
            var self = this
            this.$emit("editData");
        }
    },
    created: function() {
        var self = this
        setTimeout(function(){ 
            var index = self.getArrayIndex(self.editor_section, 'id', self.element_id)
            if (self.editor_section[index]) {
                self.editor = self.editor_section[index]
            }
            self.editor.id = self.element_id
        }, 50);
    },
};
</script>
