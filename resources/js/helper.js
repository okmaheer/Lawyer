import Vue from 'vue'
Vue.mixin({
    data () {
        return {
          reverse_slider:false,
          notificationSystem: {
            success: {
              position: 'topRight',
              timeout: 3000
            },
            error: {
              position: 'topRight',
              timeout: 4000
            },
            completed: {
              overlay: true,
              zindex: 999,
              position: 'center',
              timeout: 1000,
              progressBar: false
            },
            info: {
              overlay: true,
              zindex: 999,
              position: 'center',
              timeout: 3000,
              class: 'iziToast-info',
              id: 'info_notify'
            }
          }
        }
    },
    methods: {
        showError (message) {
        return this.$toast.error(' ', message, this.notificationSystem.error)
        },
        showCompleted (message) {
        return this.$toast.success(' ', message, this.notificationSystem.completed)
        },
        showInfo (message) {
        return this.$toast.info(' ', message, this.notificationSystem.info)
        },
        showMessage (message) {
        return this.$toast.success(' ', message, this.notificationSystem.success)
        },      
        getArrayIndex (array, attr, value) {
            var length
            if (array) {
                if (typeof array == 'object') {
                    length = Object.entries(array).length
                } else {
                    length = array.length
                }
                for (var i = 0; i < length; i += 1) {
                    if (array[i][attr] == value) {
                    return i
                    }
                }
                return -1
            }
        },
        getNastedArrayIndex (array, attr, value, nestedArray) {
            var json =[]
            for (var i = 0; i < array.length; i += 1) {
                for (var x = 0; x < array[i][nestedArray].length; x += 1) {
                    if (typeof array[i][nestedArray][x] == 'object') {
                        if (Object.entries(array[i][nestedArray][x]).length > 0) {
                            if (array[i][nestedArray][x]) {
                                for (var y = 0; y < Object.entries(array[i][nestedArray][x]).length; y += 1) {
                                    if (array[i][nestedArray][x][y][attr] == value) {
                                        json['parentIndex'] = i
                                        json['childIndex'] = x
                                        json['nastedChildIndex'] = y
                                        return json
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return -1
        },
        getHeadingTags () {
            return ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']
        },

        /**
         * Get Order List
         *
         * @param string $key key
         *
         * @access public
         *
         * @return array
         */
        getOrderList (key = '') {
            let list = {
                'left': 'Left',
                'right': 'Right',
            }
            if (key && (key in list)) {
                return list[key]
            } else {
                return list
            }
        },

        /**
         * Get header list
         *
         * @param string $key key
         *
         * @access public
         *
         * @return array
         */
        getHeaderList (key = '') {
            let list = [
                {
                    'value': 'headerv1',
                    'image': APP_URL+'/images/page-builder/headers/header1.jpg'
                }, 
                {
                    'value': 'headerv2',
                    'image': APP_URL+'/images/page-builder/headers/header2.jpg'
                }
            ]
            if (key && (key in list)) {
                return list[key]
            } else {
                return list
            }
        },
    },
    mounted () {
        if ($("body").hasClass("rtl")) {
            this.reverse_slider = true
        }
    }
})