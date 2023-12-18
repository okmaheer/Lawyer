<template>
 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5 col-xl-6 dc-responsive-mt mt-lg-0 mt-xl-0">
    <div class="dc-dashboardbox dc-messages-holder dc-openmsg dc-conversationholder">
        <div class="dc-dashboardboxtitle dc-titlemessage">
            <div class="dc-titlemessage__content">
                <figure>
                    <img :src="conversation_users.receiver_img" >
                </figure>
                <div class="dc-titlemessage__text">
                    <p>{{conversation_users.receiver_name}}</p>
                    <span>{{conversation_users.receiver_role}}</span>
                </div>
            </div>
            <div class="dc-titlemessage__content">
                <figure>
                    <img :src="conversation_users.sender_img" >
                </figure>
                <div class="dc-titlemessage__text">
                    <p>{{conversation_users.sender_name}}</p>
                    <span>{{conversation_users.sender_role}}</span>
                </div>
            </div>
        </div>
        <div class="dc-dashboardboxcontent dc-dashboardholder dc-offersmessages">
            <ul>
                <li class="d-none"></li>
                <li class="dc-conversation">
                    <div class="dc-chatarea dc-chatboxpop">
                        <div class="dc-custom-scrollbar-wrapper dc-conversationscrollbar dc-dashboardscrollbar">
                            <div class="dc-messages messages">
                                <transition-group name="list">
                                    <div v-for="msg in messages" v-bind:key="msg.id"  :id="'conv-message-'+msg.id" :ref="'message-'+msg.id" v-bind:class="[msg.is_sender==='yes' ? 'dc-memessage' : 'dc-offerermessage', msg.read_status + ' list-item']">
                                        <figure v-if="msg.image">
                                            <img :src="msg.image" >
                                        </figure>
                                        <div class="dc-description dc-tickremove">
                                            <a href="javascript:void(0);" v-on:click="deleteMessage(msg.id)" class="dc-cross">x</a>
                                            <div class="clearfix"></div>
                                            <p v-if="msg.message" v-html="msg.message"></p>
                                            <div class="clearfix"></div>
                                            <time :datetime="msg.date">{{msg.date}}</time>
                                        </div>
                                    </div>
                                </transition-group>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
</template>
<script>
export default {
    props: ['messages', 'conversation_users'],
    mounted() {
        jQuery('.dc-conversationscrollbar').mCustomScrollbar({
            axis:"y",
            scrollbarPosition: "outside",
            autoHideScrollbar: true,
            scrollTo:'bottom',
            setTop:"9999px",
            callbacks:{
                onTotalScrollBackOffset:100,
                alwaysTriggerOffsets:false
            },
            advanced:{updateOnContentResize:true} //disable auto-updates (optional)
        });
    },
    methods:{
        deleteMessage (message_id) {
            this.$swal({
                title: 'Delete Message',
                type: "warning",
                showCancelButton: true,
                customContainerClass:'hire_popup',
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true,
                showLoaderOnConfirm: true
                }).then((result) => {
                    var self = this;
                    if(result.value) {
                        axios.post(APP_URL + '/admin/conversation/delete-message', {
                            id: message_id
                        })
                        .then(function (response) {
                            if (response.data.type == 'success') {
                                var index = self.getArrayIndex(self.messages, 'id', message_id)
                                self.messages.splice(index, 1)
                            } else {
                                self.showError(response.data.message);
                            }
                        })
                    } else {
                        this.$swal.close()
                    }
            })
        }
    },
}
</script>
<style>
.list-item {
  display: inline-block;
  margin-right: 10px;
}
.list-enter-active, .list-leave-active {
  transition: all 0.5s;
}
.list-enter, .list-leave-to {
  opacity: 0;
  transform: translateX(10px);
}
</style>