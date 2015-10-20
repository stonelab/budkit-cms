/* ===================================================
 * budkit-player.js v0.0.1
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
    var BKPlayer = function(object, options) {
        this.options = $.extend({}, $.fn.bkplayer.defaults, options);
        this.element = $(object);

        var video = this.element.find('video'),
            audio = this.element.find('audio');

        this.player = video.length ? video : audio,
            this.controls = this.element.find('.controls');

        //check if audio or video
        //initialize controls
        //display #budkit-slider
        this.init();

    };
    //Uploader Class
    BKPlayer.prototype = {
        init: function() {
            //the play button;
            this.player.removeAttr('controls'); //never show the default controls

            //Controls
            this.playpause = this.controls.find('.tools > .play').bind('click', $.proxy(this.play, this));
            this.progressbar = this.controls.find('.seek > .progress');
            this.bufferbar = this.controls.find('.seek > .buffer')
            //Video Events
            this.player.bind('durationchange timeupdate', $.proxy(this.progress, this));
            //this.player.bind('contextmenu', function() {return false; })//never show the default controls;

            //If audio remove full screen;
            this.fullscreenbtn = this.controls.find('.tools > .resizefull').bind('click', $.proxy(this.fullscreen, this));

            if (this.player.prop('nodeName') === 'AUDIO') {
                this.fullscreenbtn.remove();
                this.floatingcontrols = this.element.find('.floating-controls').bind('click', $.proxy(function(e) {
                    e.preventDefault();
                    this.playpause.trigger('click');
                    this.floatingcontrols.find('i').toggleClass('icon-play-circle').toggleClass('icon-pause');
                }, this));
            }
        },
        play: function(e) {
            e.preventDefault();
            if (this.player.prop('paused')) {
                this.player.get(0).play();
                this.playpause.removeClass('icon-play').addClass('icon-pause');
            } else {
                this.player.get(0).pause();
                this.playpause.removeClass('icon-pause').addClass('icon-play');
            }
        },
        stop: function() {
        },
        seek: function() {
        },
        progress: function() {
            var progress = (this.player.prop('currentTime') / this.player.prop('duration')) * 100,
                buffered = (this.player.prop('buffered').end(0) / this.player.prop('duration')) * 100,
                fCurrentTime = this.formatTime(this.player.prop('currentTime')),
                fDuration = this.formatTime(this.player.prop('duration'));

            this.progressbar.width(progress + '%');
            this.bufferbar.width(buffered + '%');
            this.seekknob = this.controls.find('.seek > .timer-knob').css('left', progress + '%');
            this.timerDisplay = this.controls.find('.seek > .timer').text(fCurrentTime).css('left', progress + '%');

            this.controls.find('.tools > .timelog').text(fCurrentTime + '/' + fDuration);

        },
        formatTime: function(t) {
            t = Number(t);
            var h = Math.floor(t / 3600),
                m = Math.floor(t % 3600 / 60),
                s = Math.floor(t % 3600 % 60)
                ;
            return ((h > 0 ? h + ":" : "") + (m > 0 ? (h > 0 && m < 10 ? "0" : "") + m + ":" : "0:") + (s < 10 ? "0" : "") + s);
        },
        fullscreen: function(e) {
            console.log(this.element);

            var fullScreenApi = {
                    supportsFullScreen: false,
                    fullScreenEventName: '',
                    prefix: ''
                },
                fullscreenBtn = this.fullscreenbtn,
                fullscreenFig = this.element,
                browserPrefixes = 'webkit moz o ms khtml'.split(' ');
            if (typeof document.cancelFullScreen !== 'undefined') {
                fullScreenApi.supportsFullScreen = true;
            } else {
                for (var i = 0, il = browserPrefixes.length; i < il; i++) {
                    fullScreenApi.prefix = browserPrefixes[i];
                    if (typeof document[fullScreenApi.prefix + 'CancelFullScreen' ] !== 'undefined') {
                        fullScreenApi.supportsFullScreen = true;
                        break;
                    }
                }
            }
            if (fullScreenApi.supportsFullScreen) {
                fullScreenApi.fullScreenEventName = fullScreenApi.prefix + 'fullscreenchange';
                fullScreenApi.isFullScreen = function() {
                    switch (this.prefix) {
                        case '':
                            return document.fullScreen;
                        case 'webkit':
                            return document.webkitIsFullScreen;
                        default:
                            return document[this.prefix + 'FullScreen'];
                    }
                };
                fullScreenApi.requestFullScreen = function(el) {
                    return (this.prefix === '') ? el.requestFullScreen() : el[this.prefix + 'RequestFullScreen']();
                };
                fullScreenApi.cancelFullScreen = function() {
                    return (this.prefix === '') ? document.cancelFullScreen() : document[this.prefix + 'CancelFullScreen']();
                };
                this.element.bind(fullScreenApi.fullScreenEventName, function() {
                    $(this).toggleClass('fullscreen');
                    fullscreenBtn.toggleClass('icon-resize-full');
                    fullscreenBtn.toggleClass('icon-resize-small');
                });
                //Toggle Fullscreen;
                if (!fullScreenApi.isFullScreen()) {
                    fullScreenApi.requestFullScreen(fullscreenFig.get(0));
                    fullscreenBtn.removeClass('icon-resize-full');
                    fullscreenBtn.addClass('icon-resize-small');
                    //fullscreenFig.addClass('fullscreen');
                } else {
                    fullScreenApi.cancelFullScreen(fullscreenFig.get(0));
                    fullscreenBtn.removeClass('icon-resize-small');
                    fullscreenBtn.addClass('icon-resize-full');
                    fullscreenFig.removeClass('fullscreen');
                }
            }
        }
    };
    //Plugin Defintion
    $.fn.bkplayer = function(option) {
        return this.each(function() {
            var $this = $(this)
                , options = typeof option === 'object' && option;
            //I probably should not be doing this but hey?
            $this.data('bkplayer', (new BKPlayer(this, options)))
        });
    };
    $.fn.bkplayer.defaults = {};
    $.fn.bkplayer.Constructor = BKPlayer;

    //Plugin data api
    $(function() {
        $('[data-target="budkit-player"]').bkplayer();
    })
}(window.jQuery);
