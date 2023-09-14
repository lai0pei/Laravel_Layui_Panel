<script>
    layui.use(['jquery', 'util', 'layer'], function() {
        var $ = layui.jquery;
        var util = layui.util;
        util.event('lay-event', {
            'fileUpload': function(data) {
                var ext = this.getAttribute('lay-ext');
                var target = this.getAttribute('lay-target');
                var preview = this.getAttribute('lay-preview');
                var server = this.getAttribute('lay-type');

                if (ext == null) {
                    ext = '';
                }

                if (preview == null) {
                    preview = '';
                }

                if (target == null) {
                    target = '';
                }

                if (server == null) {
                    server = '';
                }

                iframe_open({
                    "url": "{{route('f_commonUpload')}}?lay-ext=" + ext + "&lay-target=" + target + "&lay-preview=" + preview + "&lay-type=" + server
                    , "title": "附件库"
                    , "size": ["90%", "90%"]
                });
            }
        });

       
    })

</script>
