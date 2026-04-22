/**
 * MaximaTV: optional quality menu for Video.js (multiple MP4 sources).
 */
(function (window, videojs) {
  'use strict';

  if (!videojs) {
    return;
  }

  function getOrInitPlayerSwitchState(player) {
    if (!player._maximaQualityState) {
      player._maximaQualityState = { lastStableTime: 0, switchGen: 0 };
      player.on('timeupdate', function () {
        var c = player.currentTime();
        if (!isNaN(c) && c > 0.25) {
          player._maximaQualityState.lastStableTime = c;
        }
      });
    }
    return player._maximaQualityState;
  }

  function switchSource(player, src) {
    var state = getOrInitPlayerSwitchState(player);
    var t = player.currentTime();
    if (t < 0.25 && state.lastStableTime > 0) {
      t = state.lastStableTime;
    }
    var wasPaused = player.paused();
    state.switchGen += 1;
    var gen = state.switchGen;
    player.src({ src: src.src, type: 'video/mp4' });
    player.one('loadedmetadata', function () {
      if (gen !== state.switchGen) {
        return;
      }
      try {
        player.currentTime(t);
      } catch (e) {}
      if (!wasPaused) {
        var p = player.play();
        if (p && typeof p.catch === 'function') {
          p.catch(function () {});
        }
      }
    });
  }

  var MenuButton = videojs.getComponent('MenuButton');
  var MenuItem = videojs.getComponent('MenuItem');

  var QualityMenuButton = videojs.extend(MenuButton, {
    constructor: function (player, options) {
      if (options === void 0) {
        options = {};
      }
      options.title = options.title || 'Качество';
      MenuButton.call(this, player, options);
    },
    buildCSSClass: function () {
      return (
        'vjs-maxima-quality-button ' + MenuButton.prototype.buildCSSClass.call(this)
      );
    },
    buildWrapperCSSClass: function () {
      return (
        'vjs-maxima-quality-button ' +
        MenuButton.prototype.buildWrapperCSSClass.call(this)
      );
    },
    createItems: function () {
      var player = this.player();
      var sources = this.options_.sources || [];
      var items = [];

      for (var i = 0; i < sources.length; i++) {
        (function (src) {
          var item = new MenuItem(player, {
            label: src.label,
            selectable: true,
            selected: !!src.selected
          });
          item.on('click', function () {
            for (var j = 0; j < items.length; j++) {
              items[j].selected(false);
            }
            item.selected(true);
            switchSource(player, src);
          });
          items.push(item);
        })(sources[i]);
      }

      return items;
    }
  });

  QualityMenuButton.prototype.controlText_ = 'Качество';
  if (!videojs.getComponent('QualityMenuButton')) {
    videojs.registerComponent('QualityMenuButton', QualityMenuButton);
  }

  window.MaximaVideo = window.MaximaVideo || {};

  /**
   * @param {string} playerId
   */
  window.MaximaVideo.initPlayer = function (playerId) {
    var sources = window.__MaximaTVVideoSources;
    if (!sources || !sources.length) {
      sources = [];
    }

    var player =
      videojs.getPlayer(playerId) ||
      videojs(playerId, {
        fluid: true
      });

    player.ready(function () {
      getOrInitPlayerSwitchState(this);

      this.hotkeys({
        volumeStep: 0.1,
        seekStep: 5,
        enableModifiersForNumbers: false
      });

      if (sources.length > 1) {
        var cb = this.getChild('controlBar');
        if (cb && !cb.getChild('QualityMenuButton')) {
          cb.addChild(
            'QualityMenuButton',
            { sources: sources },
            Math.max(0, cb.children().length - 1)
          );
        }
      }
    });
  };
})(window, window.videojs);
