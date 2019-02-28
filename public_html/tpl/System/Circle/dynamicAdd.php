<include file="Public:header"/>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<form id="myform" method="post" action="{pigcms{:U('Circle/editdynamic')}" enctype="multipart/form-data">
  <input type="hidden" name="id" value="{pigcms{$now_category.id}"/>
  <input type="hidden" name="uid" value="{pigcms{$now_category.uid}"/>
    <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
        <tr>
            <th width="80">标题</th>
            <td><input type="text" class="input fl" name="title" size="75" placeholder="资讯标题" validate="maxlength:50" value="{pigcms{$now_category.title}"/></td>
        </tr>
        <tr>
            <th width="80">圈子类别</th>
            <td>
                <select class="category" name="fid" style="margin-right:15px;">
                <volist name="quanzi" id="vo">
				<if condition="$vo['id'] eq $now_category['circle_id']">
				<option value='{pigcms{$vo.id}' selected>{pigcms{$vo.name}</option>
				<else/><option value='{pigcms{$vo.id}' >{pigcms{$vo.name}</option>
				</if>
				</volist>
                    
                </select>

            </td>
        </tr>
        <tr>
            <th width="800">封面图</th>
            <td id="fengmian">
                <input type="hidden" value="{pigcms{$now_category.image}" class="input fl" id="image" name="image" />
                <if condition="$now_category['image']">
                    <img src='{pigcms{$config.site_url}{pigcms{$now_category.image}' style='width:100px;height:100px;'><input type="button" value="×" onclick="mydel(this);">
                </if>
            </td>
        </tr>
        <tr>
            <th width="80">添加封面图</th>
            <td>
             <a href="javascript:void(0)" class="button" id="image2">浏览</a>
            </td>
        </tr>
         <tr id="showpic">
            <th width="80">展示图</th>
            <td >
        <if condition="is_array($imgurl)">
            <volist name="imgurl" id="vo">
            <div style="float: left;"><input type="hidden" value="{pigcms{$vo}" name="img_url[]"/><img src="{pigcms{$config.site_url}{pigcms{$vo}" style='width:100px;height:100px; '><input type="button" value="×" onclick="mydel(this);"><br/></div>
            </volist>
        </if>

            </td>
        </tr>
        <tr>
            <th width="80">添加展示图</th>
            <td>
            <a href="javascript:void(0)" class="button" id="image3">浏览</a>
            </td>
        </tr>

        
        <tr  height="200">
            <th width="80" >详细内容</th>
            <td>
                <textarea name="content" id="content">{pigcms{$now_category.content}</textarea>
            </td>
        </tr>
        <tr>
            <th width="80">阅读数量</th>
            <td><input type="text" class="input fl" name="read_num" size="75" placeholder="阅读数量" validate="maxlength:50" value="{pigcms{$now_category.read_num}" id="read_num"/></td>
        </tr>
        <tr>
				<th width="80">是否置顶</th>
				<td>
					<span class="cb-enable">
					<label class="cb-enable <if condition="$now_category['ding'] eq 0">selected</if>"><span>置顶</span><input type="radio" name="ding" value="0"  <if condition="$now_category['ding'] eq 0">checked="checked"</if> /></label>
					</span>
					<span class="cb-disable">
					<label class="cb-disable <if condition="$now_category['ding'] eq 1">selected</if>"><span>不置顶</span><input type="radio" name="ding" value="1"  <if condition="$now_category['ding'] eq 1">checked="checked"</if> /></label>
					</span>
				</td>
			</tr>
       <tr>
				<th width="80">显示状态</th>
				<td>
					<span class="cb-enable">
					<label class="cb-enable <if condition="$now_category['status'] eq 0">selected</if>"><span>显示</span><input type="radio" name="status" value="0"  <if condition="$now_category['status'] eq 0">checked="checked"</if> /></label>
					</span>
					<span class="cb-disable">
					<label class="cb-disable <if condition="$now_category['status'] eq 1">selected</if>"><span>隐藏</span><input type="radio" name="status" value="1"  <if condition="$now_category['status'] eq 1">checked="checked"</if> /></label>
					</span>
				</td>
			</tr>
    </table>
    <div class="btn hidden">
        <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
        <input type="reset" value="取消" class="button" />
    </div>
</form>

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
            uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=Circle/editor"
        });
    });

</script>
<script>
            KindEditor.ready(function(K) {
                var editor = K.editor({
                    allowFileManager : true
                });
                K('#image3').click(function() {
                    editor.uploadJson = "{pigcms{:U('Circle/ajax_upload_pic')}";
                    editor.loadPlugin('image', function() {
                        editor.plugin.imageDialog({
                            showRemote : false,
                            imageUrl : K('#url3').val(),
                            clickFn : function(url, title, width, height, border, align) {

                                var html="<input type='hidden' value='"+url+"' style='width:400px;' name='img_url[]'>";
                                var html2="<input type='button' value='×' onclick='mydel(this);'><br/>";
                                var pic = "<img src='{pigcms{$config.site_url}"+url+"' style='width:100px;height:100px;'>";
                                K('#showpic td').append(html);
                               // K('#showpic td').append(html2);
                                K('#showpic td').append(pic);
                                K('#showpic td').append(html2);
                                //K('#listImg').val(url);
                                editor.hideDialog();
                            }
                        });
                    });
                });

               K('#image2').click(function() {
                    editor.uploadJson = "{pigcms{:U('Circle/ajax_upload_pic')}";
                    editor.loadPlugin('image', function() {
                        editor.plugin.imageDialog({
                            showRemote : false,
                            imageUrl : K('#url3').val(),
                            clickFn : function(url, title, width, height, border, align) {

                                var html2="<input type='button' value='×' onclick='mydel(this);'><br/>";
                                var pic = "<img src='{pigcms{$config.site_url}"+url+"' style='width:100px;height:100px;'>";
                                K('#image').val(url);
                                K('#fengmian').append(pic);
                                K('#fengmian').append(html2);
                                
                                editor.hideDialog();
                            }
                        });
                    });
                });


            });

function mydel(obj){
$(obj).prev().prev().val('');
$(obj).prev().hide();
$(obj).prev().prev().hide();
$(obj).hide();
}
</script>
<include file="Public:footer"/>