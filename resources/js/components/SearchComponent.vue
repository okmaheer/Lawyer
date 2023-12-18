<template>
    <div :id="typeahead_id">
        <input class="typeahead" type="text" :placeholder="trans('lang.search_doc_hos_spec_serv')"  name='query' v-model="query">
    </div>
</template>
<script>
export default {
    props:['typeahead_id'],
    data () {
        return {
            searchable_data:[],
            finalResult:[],
            query:'',
            is_show:false
        }
    },
    mounted () {
        setTimeout(() => {
            
            var doctors = new Bloodhound({
            
            remote: {
                url: APP_URL +'/search/get-searchable-data?query=%QUERY%&&type=doctor',
                wildcard: '%QUERY%'
            },
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            
            });
    
            var hospitals = new Bloodhound({
            
            remote: {
                url: APP_URL +'/search/get-searchable-data?query=%QUERY%&&type=hospital',
                wildcard: '%QUERY%'
            },
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            
            });
    
            var services = new Bloodhound({
            
            remote: {
                url: APP_URL +'/search/get-searchable-data?query=%QUERY%&&type=service',
                wildcard: '%QUERY%'
            },
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            
            });
    
            var specialities = new Bloodhound({
            
            remote: {
                url: APP_URL +'/search/get-searchable-data?query=%QUERY%&&type=speciality',
                wildcard: '%QUERY%'
            },
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            
            });
           
            var self = this
            $('#'+self.typeahead_id + ' .typeahead').typeahead({
                highlight: true,
                minLength: 1,
                },
                {
                    name: 'search-doctors',
                    display: 'name',
                    source: doctors.ttAdapter(),
                    templates: {
                        empty: [
                            '<h3 class="league-name">Doctors</h3><div class="list-group search-results-dropdown"><div class="search-list-group-item">No doctor found.</div></div>'
                        ],
                        header: ['<h3 class="league-name">Doctors</h3>'],
                        footer (data) {  
                            if (data.suggestions.length >= 5) {
                            return '<a href="'+APP_URL+'/search-results?search='+ self.query +'&locations=&type=doctor&speciality=">Show all doctors related to <strong>'+ self.query +'</strong></a>'
                        }},
                        suggestion: function (data) {
                            return '<a href="'+APP_URL+'/profile/' + data.slug + '" class="search-list-group-item">' + data.name + '</a>'
                        }
                    }
                },
                {
                    name: 'search-hospitals',
                    display: 'name',
                    source: hospitals.ttAdapter(),
                    templates: {
                        empty: [
                            '<h3 class="league-name">Hospitals</h3><div class="list-group search-results-dropdown"><div class="search-list-group-item">No hospital found.</div></div>'
                        ],
                        header: ['<h3 class="league-name">Hospitals</h3>'],
                        footer (data) {  
                            if (data.suggestions.length >= 5) {
                            return '<a href="'+APP_URL+'/search-results?search='+ self.query +'&locations=&type=hospital&speciality=">Show all hospitals related to <strong>'+ self.query +'</strong></a>'
                        }},
                        suggestion: function (data) {
                            return '<a href="'+APP_URL+'/profile/' + data.slug + '" class="search-list-group-item">' + data.name + '</a>'
                        }
                    }
                },
                {
                    name: 'search-services',
                    display: 'name',
                    source: services.ttAdapter(),
                    templates: {
                        empty: [
                            '<h3 class="league-name">Services</h3><div class="list-group search-results-dropdown"><div class="search-list-group-item">No service found.</div></div>'
                        ],
                        header: ['<h3 class="league-name">Services</h3>'],
                        footer (data) {  
                            if (data.suggestions.length >= 5) {
                            return '<a href="'+APP_URL+'/search-results?search=&locations=&type=both&speciality=&service='+ self.query +'">Show all services related to <strong>'+ self.query +'</strong></a>'
                        }},
                        suggestion: function (data) {
                            return '<a href="'+APP_URL+'/search-results?service=' + data.slug + '" class="search-list-group-item">' + data.name + '</a>'
                        }
                    }
                },
                {
                    name: 'search-specialities',
                    display: 'name',
                    source: specialities.ttAdapter(),
                    templates: {
                        empty: [
                            '<h3 class="league-name">Specialities</h3><div class="list-group search-results-dropdown"><div class="search-list-group-item">No speciality found.</div></div>'
                        ],
                        header: ['<h3 class="league-name">Specialities</h3>'],
                        footer (data) {  
                            if (data.suggestions.length >= 5) {
                            return '<a href="'+APP_URL+'/search-results?search=&locations=&type=both&speciality='+ self.query +'&service=">Show all specialities related to <strong>'+ self.query +'</strong></a>'
                        }},
                        suggestion: function (data) {
                            return '<a href="'+APP_URL+'/search-results?speciality=' + data.slug + '" class="search-list-group-item">' + data.name + '</a>'
                        }
                    }
                }
            );
        }, 500);
    },
}
</script>