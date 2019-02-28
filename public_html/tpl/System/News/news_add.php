<include file="Public:header"/>
<form id="myform" method="post" action="{pigcms{:U('News/create_data')}" enctype="multipart/form-data">
    <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
        <tr>
            <th width="80">资讯标题</th>
            <td><input type="text" class="input fl" name="title" size="75" placeholder="资讯标题" validate="maxlength:50,required:true"/></td>
        </tr>
        <tr>
            <th width="80">短标题</th>
            <td><input type="text" class="input fl" name="title_short" size="75" placeholder="短标题" validate="maxlength:50,required:true"/></td>
        </tr>

        <tr>
            <th width="80">资讯标签</th>
            <td><input type="text" class="input fl" name="tag_name" size="55" placeholder="标签" validate="maxlength:35,required:true"/>(多个标签用英文","号隔开)</td>
        </tr>

        <tr>
            <th width="80">所在地</th>
            <td id="choose_cityarea" area_id="-1" circle="-1"></td>
        </tr>

        <tr>
            <th width="80">封面图</th>
            <td><input type="file" class="input fl" name="cover_img" style="width:200px;" placeholder="封面图" validate="required:true"/></td>
        </tr>

        <tr>
            <th width="90">资讯状态</th>
            <td>
                <span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="is_display" value="1" checked="checked" /></label></span>
                <span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="is_display" value="0" /></label></span>
            </td>
        </tr>

        <tr>
            <th width="80">类别</th>
            <td>
                <select class="category" name="category_pid" style="margin-right:15px;">
                    <option value=""> == 请选择 == </option>
                    <?php if(is_array($categoryList)) {?>
                        <?php foreach($categoryList as $list){?>
                            <option value="<?php echo $list['category_id']?>"><?php echo $list['name']?></option>
                        <?php }?>
                    <?php }?>
                </select>

            </td>
        </tr>

        <tr>
            <th width="80">内容摘要</th>
            <td><textarea name="abstract" id="abstract"  style="margin: 0px; width: 406px; height: 124px;"></textarea></td>
        </tr>

        <tr>
            <th width="80">详细内容</th>
            <td>
                <textarea name="content" id="content"></textarea>
            </td>
        </tr>

    </table>
    <div class="btn hidden">
        <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
        <input type="reset" value="取消" class="button" />
    </div>
</form>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script type="text/javascript">
    KindEditor.ready(function(K){
        kind_editor = K.create("#content",{
            width:'350px',
            height:'150px',
            resizeType : 1,
            allowPreviewEmoticons:false,
            allowImageUpload : true,
            filterMode: true,
            items : [
                'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                'insertunorderedlist', '|', 'emoticons', 'image', 'link'
            ],
            emoticonsPath : './static/emoticons/',
            uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=fc/school"
        });
    });

    $(function(){
        $('.category').change(function(){
            var category_id = $(this).val();

            if(category_id == ''){
                $('.category-child,.category-child-son').remove();
                return false;
            }
            $('.category-child').remove();
            $.post("{pigcms{:U('News/getCategoryList')}",{'category_id':category_id},function(data){
                if(data.err_code == 1){
                    var html = "<select class='category-child' name='category_child_id' style='margin-right:15px;'>";
                    html += "<option value=''> == 请选择 == </option>";

                    for( var i in data.err_msg) {
                        html += "<option value="+data.err_msg[i].category_id+">"+data.err_msg[i].name+"</option>";
                    }
                    html += "</select>";

                    $('.category').after(html);
                }
            },'json')
        });

        $('.category-child').live('change',function(){
            var category_id = $(this).val();
            $('.category-child-son').remove();
            $.post("{pigcms{:U('News/getCategoryList')}",{'category_id':category_id},function(data){

                if(data.err_code == 1){
                    var html = "<select class='category-child-son' name='category_grandson_id'>";
                    html += "<option value=''> == 请选择 == </option>";

                    for( var i in data.err_msg) {
                        html += "<option value="+data.err_msg[i].category_id+">"+data.err_msg[i].name+"</option>";
                    }
                    html += "</select>";
                    if(data.level == 3){
                        $('.category-child').after(html);
                    }
                }
            },'json')
        });
    });
</script>

<include file="Public:footer"/>