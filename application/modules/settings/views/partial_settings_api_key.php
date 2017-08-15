<script>
    $(function () {
        $('#btn_generate_api_key').click(function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url('api/key/'); ?>",
                type: 'PUT',
                success: function (data) {
                    console.log(data);
                    if(data.status) {
                        $('#settings_api_key').val(data.key);
                    } else {
                        alert(data.message);
                    }
                },
                error: function () {
                    console.log("error");
                }
            });
        });
    });
</script>

<div class="col-xs-12 col-md-8 col-md-offset-2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php _trans('api_key'); ?>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label for="settings[api_key]">
                            <?php _trans('api_key'); ?>
                        </label>
                          <input type="text" name="settings[api_key]" id="settings_api_key"
                               class="form-control"
                               value="<?php echo get_setting('api_key', '', true); ?>" disabled>
                               <a href="#" id="btn_generate_api_key" class="btn btn-primary">Generate</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
