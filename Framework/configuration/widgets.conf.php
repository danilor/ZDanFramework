<?php
$twitterWidget = "
    <script type=\"text/javascript\">
                                new TWTR.Widget({
                                  version: 2,  type: 'search',  search: '@android',  interval: 30000,
                                  title: 'Lo que la gente está diciendo de',  subject: 'Android!',
                                  width: 'auto',  height: 300,
                                  theme: {
                                    shell: {
                                      background: '#89a834',
                                      color: '#ffffff'
                                    },
                                    tweets: {
                                      background: '#ffffff',
                                      color: '#444444',
                                      links: '#1985b5'
                                    }
                                  },
                                  features: {
                                    scrollbar: false,    loop: true,    live: true,    hashtags: true,    timestamp: true,    avatars: true,    toptweets: true,    behavior: 'default'
                                  }
                                }).render().start();
                                </script>
";
DEFINE("WIDGET_TWITTER",$twitterWidget);

?>
