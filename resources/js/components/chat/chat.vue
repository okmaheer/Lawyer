<template>
    <div class="dc-chatpopup">
        <div class="dc-chatbox">
            <div class="dc-chatboxpop"  id="dc-verticalscrollbarpop">
                <div class="dc-messages messages">
                    <div v-for="(msg, index) in messages" :key="index" v-bind:class="[msg.type===1 ? 'dc-offerermessage' : 'dc-memessage dc-readmessage']">
                        <figure v-if="image">
                            <img v-if="msg.type===1" :src="msg.by" :alt="image_name">
                            <img v-else :src="msg.image" :alt="image_name">
                        </figure>
                        <div class="dc-description">
                            <p>{{ msg.message }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dc-replaybox">
                <div class="form-group">
                    <textarea class="form-control" name="reply" :placeholder="ph_new_msg" v-model="newmessage"></textarea>
                </div>
                <div class="dc-iconbox">
                    <a href="javascript:void(0);" @click="sendMessage" class="dc-btnsendmsg">{{ trans('lang.btn_send') }}</a>
                </div>
            </div>
        </div>
        <a id="dc-getsupport" class="dc-themeimgborder"><img :src="this.receiver_profile_image" :alt="trans_image_alt"></a>
    </div>
</template>

<script>
export default {
    props: ['receiver_id', 'receiver_profile_image', 'trans_image_alt', 'ph_new_msg', 'empty_error'],
    data() {
        return {
            user: Laravel.user.name,
            image:Laravel.user.image,
            image_name:Laravel.user.image_name,
            newmessage: '',
            messages: [],
            receiver: this.receiver_id,
            notificationSystem: {
                error: {
                    position: "topRight",
                    timeout: 4000
                }
            },
        }
    },
    methods: {
        showError(error){
            return this.$toast.error(' ', error, this.notificationSystem.error);
        },
        sendMessage(e) {
            e.preventDefault();
            if (this.newmessage) {
                var self = this;
                this.messages.push({ message: this.newmessage,image: this.image, type: 0, by: 'Me' })
                jQuery('#dc-verticalscrollbarpop').mCustomScrollbar('scrollTo','bottom');
                axios.post(APP_URL + '/message/send-private-message',{
                    author_id : Laravel.user.id,
                    receiver_id: self.receiver,
                    message: self.newmessage,
                    status: 0
                })
                .then(function (response) { })
                .catch(function (error) {});

                this.newmessage = null
            } else {
                this.showError(this.empty_error);
            }
        },
        startChat (id) {
                let self = this;
                axios.post(APP_URL + '/message-center/get-messages',{
                    sender_id : id,
                    type : 'popup'
                })
                .then(function (response) {
                    if (response.data.messages) {
                        self.messages = response.data.messages;
                    }
                });
        },
    },
    mounted () {
        let self = this;
        jQuery('#dc-btnclosechat, #dc-getsupport').on('click', function () {
            jQuery('.dc-chatbox').slideToggle();
            jQuery('#dc-verticalscrollbarpop').mCustomScrollbar({
                axis:"y",
                scrollbarPosition: "outside",
                autoHideScrollbar: true,
                scrollTo:'bottom',
                callbacks:{
                    onTotalScrollBackOffset:100,
                    alwaysTriggerOffsets:false
                },
                advanced:{updateOnContentResize:true} //disable auto-updates (optional)
            });
        });

    },
    created () {
        this.startChat(this.receiver)
    }
}
</script>