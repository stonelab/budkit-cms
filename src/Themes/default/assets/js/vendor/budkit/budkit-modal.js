/* ===================================================
 * budkit-modal.js v0.0.1
 * http://budkit.org/docs/editor
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
    var BKModal = function(object, options) {
        this.options = $.extend({}, $.fn.bkmodal.defaults, $(object).data(), options);
        this.toggler = $(object),
            //this.items = this.timeline.children('li'); //Pull up only the immediate children
            this.dialog = $('[data-target="budkit-modal"]').last().clone(),
            this.dialogbody = this.dialog.find('.modal-body'), //last in case there are multiples, we only need one;
            this.dialogtitle = this.dialog.find('.modal-title'),
            this.dialogFooter = this.dialog.find('.modal-footer'),
            this.dialogSubmit  = this.dialogFooter.find('.modal-submit:last'),
            this.title = this.options.title,
            this.action = this.options.action;

        //e.preventDefault()
        if(this.action) this.dialogbody.load(this.action);
        if(this.title) this.dialogtitle.text(this.title);
        if(this.options.submit && $.isFunction(this.options.submit)){
            var $modal = this;
            this.dialogSubmit.on('click', $.proxy(this.options.submit , this, $modal ) );
        }else{
            //hide the submit button if we are not showing any submit;
            this.dialogFooter.hide();
        }
        this.dialog.modal('toggle').on('hidden.bs.modal', function (e) {
            // @TODO check that we are not closing the diaglog without submitting any changes
        });
    };
    //Timeline Class
    BKModal.prototype = {
        load: function() {
        },
        comment: function() {
        },
        rate: function() {
        } //Will need a seperate BKRating class;
    };
    //Plugin Defintion
    $.fn.bkmodal = function(option) {
        return this.each(function() {
            var $this = $(this)
                , options = typeof option == 'object' && option;
            //I probably should not be doing this but hey?
            $this.data('bkmodal', (new BKModal(this, options)))
        });
    };
    $.fn.bkmodal.defaults = {};
    $.fn.bkmodal.Constructor = BKModal;

    //Plugin data api
    $(function() {
        $(document).on("click.modal-toggle", '[data-toggle="budkit-modal"]', function(e) {
            e.preventDefault();
            $(this).bkmodal();
        });
    })
}(window.jQuery);