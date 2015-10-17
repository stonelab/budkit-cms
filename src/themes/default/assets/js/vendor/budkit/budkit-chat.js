/* ===================================================
 * budkit-chat.js v0.0.1
 * http://budkit.org/docs/chat
 * ===================================================
 * Copyright 2012 The BudKit Team
 *
 * This source file is subject to version 3.01 of the GNU/GPL License
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/gpl.txt  If you did not receive a copy of
 * the GPL License and are unable to obtain it through the web, please
 * send a note to support@stonyhillshq.com so we can mail you a copy immediately.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */
!function($) {
    "use strict"
    var BKChat = function(options) {

        this.options = {};
        this.options.form = $('.chat-input').find('form');
        this.options.field = $(this.options.form).find('[name="media_content"]');
        this.options.thread = $('.chat-thread').find(' .stream')

        try{
            this.socket = new WebSocket('ws://0.0.0.0:8080/echo');
            console.log('WebSocket - status '+this.socket.readyState);
            this.socket.onopen    = this.onOpen;
            this.socket.onmessage = $.proxy(this.onMessage, this);
            this.socket.onclose   = this.onClose;
        }
        catch(ex){
            console.log(ex);
        }

        //Chat form;
        $(this.options.form).bind('submit', $.proxy(function(e){
            e.preventDefault();
            console.log();
            this.socket.send(this.options.field.val());
            //reset the composer field
            this.options.field.val();
            this.options.field.data("editor").empty();
        }, this));
    };

    //Uploader Class
    BKChat.prototype = {
        onOpen: function(msg){
            console.log("Welcome - status "+this.readyState);
        },
        onClose : function(msg){
            console.log("Disconnected - status "+this.readyState);
        },
        onMessage: function(msg){
            console.log(msg.data);
            var $this = this,
                item = $('<li/>').addClass('stream-item chat-message'),
                message = $('<a />').addClass('message'),
                content = $('<div />').addClass('content').html(msg.data).appendTo(message);

            $($this.options.thread)
            .append( item.prepend( message ) )
            .closest('.chat-thread').animate({
                scrollTop:item.position().top+item.height()
            }, 600);
               // .scrollTop( item.position().top );

            //console.log(item.position());




        }
    };
    //Plugin Defintion
    $.fn.bkchat = function(option) {
        return this.each(function() {
            var $this = $(this)
                ,options = typeof option === 'object' && option;
            //I probably should not be doing this but hey?
            $this.data('bkchat', (new BKChat(this, options)))
        });
    };
    $.fn.bkchat.defaults = {};
    $.fn.bkchat.Constructor = BKChat;


}(window.jQuery);