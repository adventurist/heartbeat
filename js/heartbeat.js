/**
 * Created by logicp on 5/28/17.
 */
(function($, Drupal, drupalSettings) {
    Drupal.behaviors.heartbeat = {
        attach: function (context, settings) {

          if (drupalSettings.friendData != null) {
            var divs = document.querySelectorAll('.flag-friendship a.use-ajax');
            // console.log(divs);
            for (let i = 0; i < divs.length; i++) {
              let anchor = divs[i];
              var userId = anchor.href.substring(anchor.href.indexOf('friendship') + 11, anchor.href.indexOf('?destination'));
              console.log(userId);
              JSON.parse(drupalSettings.friendData).forEach(function (friendship) {
                if (friendship.uid_target === userId && friendship.uid == drupalSettings.user.uid && friendship.status == 0) {
                  anchor.innerHTML = 'Friendship Pending';
                }
              });
            }
            // divs.forEach(function (anchor) {
            //   var userId = anchor.href.substring(anchor.href.indexOf('friendship') + 11, anchor.href.indexOf('?destination'));
            //   console.log(userId);
            //   JSON.parse(drupalSettings.friendData).forEach(function (friendship) {
            //     if (friendship.uid_target === userId && friendship.uid == drupalSettings.user.uid && friendship.status == 0) {
            //       anchor.innerHTML = 'Friendship Pending';
            //     }
            //   });
            // });
          }

          feedElement = document.querySelector('.heartbeat-stream');

          if (drupalSettings.feedUpdate == true) {
            updateFeed();
          }

            Drupal.AjaxCommands.prototype.selectFeed = function(ajax, response, status) {

              $.ajax({
                type:'POST',
                url:'/heartbeat/render_feed/' + response.feed,
                success: function(response) {

                  feedElement = document.querySelector('.heartbeat-stream');
                  console.dir(feedElement);

                  if (feedElement != null) {

                    feedElement.innerHTML = response;

                  } else {

                    feedBlock = document.getElementById('block-heartbeatblock');
                    insertNode = document.createElement('div');
                    insertNode.innerHTML = response;
                    feedBlock.appendChild(insertNode);

                  }

                }
              });
            };

            Drupal.AjaxCommands.prototype.updateFeed = function(ajax, response, status) {
              console.dir(response.timestamp);
              if ($response.update) {
                $.ajax({
                  type: 'POST',
                  url:'/heartbeat/update_feed/' + response.timestamp,
                  success: function(response) {

                    console.dir(response);
                  }
                });
              }
            };

            listenImages();
            listenCommentPost();
        }
    };


  function updateFeed() {

    $.ajax({
      type: 'POST',
      url: '/heartbeat/form/heartbeat_update_feed',
      success: function (response) {
        console.dir(response);
        console.log('We are succeed!');
      }
    })
  }

  function listenImages() {
    let cboxOptions = {
      width: '95%',
      height: '95%',
      maxWidth: '960px',
      maxHeight: '960px',
    };

    $('.heartbeat-content').find('img').each(function() {
      $(this).colorbox({href: $(this).attr('src'), cboxOptions});
    });
  }

  function listenCommentPost() {
    let comments = document.querySelectorAll('[data-drupal-selector]');

    for (let i = 0; i < comments.length; i++) {
      let comment = comments[i];
      console.dir(comment);
      comment.addEventListener('click', function() {
        getParent(comment);
      })
    }
  }

  function getParent(node) {
    console.dir(node);
    if (node.classList.contains('heartbeat-comment')) {
      let id = node.id.substr(node.id.indexOf('-')+1);
      $.ajax({
        type: 'POST',
        url:'/heartbeat/commentupdate/' + id,
        success: function(response) {

          console.log(response);
        }
      });
    } else {
      getParent(node.parentNode);
    }
  }


})(jQuery, Drupal, drupalSettings);

