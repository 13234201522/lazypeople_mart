<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:75:"E:\phpstudy_pro\WWW\lanren\public/../application/admin\view\goods\edit.html";i:1605174409;s:69:"E:\phpstudy_pro\WWW\lanren\application\admin\view\layout\default.html";i:1588765311;s:66:"E:\phpstudy_pro\WWW\lanren\application\admin\view\common\meta.html";i:1588765311;s:68:"E:\phpstudy_pro\WWW\lanren\application\admin\view\common\script.html";i:1588765311;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !\think\Config::get('fastadmin.multiplenav')): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <form id="edit-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Category_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-category_id" data-rule="required" data-source="category/selectpage?isTree=1" data-order-by='weigh desc' class="form-control selectpage" name="row[category_id]" type="text" value="<?php echo htmlentities($row['category_id']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-name" data-rule="required" class="form-control" name="row[name]" type="text" value="<?php echo htmlentities($row['name']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Images'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-images" data-rule="required" class="form-control" size="50" name="row[images]" type="text" value="<?php echo htmlentities($row['images']); ?>">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-images" class="btn btn-danger plupload" data-input-id="c-images" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="true" data-preview-id="p-images"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                    <span><button type="button" id="fachoose-images" class="btn btn-primary fachoose" data-input-id="c-images" data-mimetype="image/*" data-multiple="true"><i class="fa fa-list"></i> <?php echo __('Choose'); ?></button></span>
                </div>
                <span class="msg-box n-right" for="c-images"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-images"></ul>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Detail'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea id="c-detail" data-rule="required" class="form-control editor" rows="5" name="row[detail]" cols="50"><?php echo htmlentities($row['detail']); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Json'); ?>:</label>
        <div class="col-xs-12 col-sm-8">

            <dl class="fieldlist" data-template="basictpl" data-name="row[json]">
                <dd  style="display: flex;justify-content: space-between;">
                    <ins><?php echo __('规格'); ?></ins>
                    <ins><?php echo __('价格'); ?></ins>
                    <ins><?php echo __('库存'); ?></ins>
                    <ins><?php echo __('图片'); ?></ins>
                    <ins><?php echo __('操作'); ?></ins>
                </dd>
                <dd><a href="javascript:;" class="btn btn-sm btn-success btn-append"><i class="fa fa-plus"></i> <?php echo __('Append'); ?></a></dd>
                <textarea name="row[json]" class="form-control hide" cols="30" rows="5"><?php echo htmlentities($row['json']); ?></textarea>
            </dl>

            <script id="basictpl" type="text/html">
                <dd class="form-inline options"  style="display: flex;justify-content: flex-start;">
                    <div  style="width: 90%;display: flex;justify-content: space-between;">
                        <ins >
                            <input type="text" data-rule="required" name="<%=name%>[<%=index%>][specs]" class="form-control" placeholder="规格" value="<%=row.specs%>">
                        </ins>
                        <ins>
                            <input type="number" data-rule="required" name="<%=name%>[<%=index%>][price]" class="form-control" placeholder="价格" value="<%=row.price%>">
                        </ins>
                        <ins>
                            <input type="number" data-rule="required" name="<%=name%>[<%=index%>][stock]" class="form-control"  placeholder="库存" value="<%=row.stock%>">
                        </ins>
                        <ins>
                            <div class="input-group">
                                <input id="c-image-<%=index%>" data-rule="required" class="form-control" name="<%=name%>[<%=index%>][specs_image]" type="textarea" value="<%=row.specs_image%>">
                                <div class="input-group-addon no-border no-padding">
                                    <span><button type="button" id="plupload-image-<%=index%>" class="btn btn-danger plupload" data-input-id="c-image-<%=index%>" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-preview-id="p-image-<%=index%>"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                                </div>
                                <span class="msg-box n-right" for="c-image-<%=index%>"></span>
                            </div>
                            <ul class="row list-inline plupload-preview" style="" id="p-image-<%=index%>">

                            </ul>
                        </ins>
                        <ins>

                        </ins>
                    </div>
                    <!--下面的两个按钮务必保留-->
                    <div>
                        <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-times"></i></span>
                        <span class="btn btn-sm btn-primary btn-dragsort"><i class="fa fa-arrows"></i></span>
                    </div>
                </dd>
            </script>

        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Hot_switch'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            
            <input  id="c-hot_switch" name="row[hot_switch]" type="hidden" value="<?php echo $row['hot_switch']; ?>">
            <a href="javascript:;" data-toggle="switcher" class="btn-switcher" data-input-id="c-hot_switch" data-yes="1" data-no="0" >
                <i class="fa fa-toggle-on text-success <?php if($row['hot_switch'] == '0'): ?>fa-flip-horizontal text-gray<?php endif; ?> fa-2x"></i>
            </a>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Vip_switch'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            
            <input  id="c-vip_switch" name="row[vip_switch]" type="hidden" value="<?php echo $row['vip_switch']; ?>">
            <a href="javascript:;" data-toggle="switcher" class="btn-switcher" data-input-id="c-vip_switch" data-yes="1" data-no="0" >
                <i class="fa fa-toggle-on text-success <?php if($row['vip_switch'] == '0'): ?>fa-flip-horizontal text-gray<?php endif; ?> fa-2x"></i>
            </a>
        </div>
    </div>
<!--    <div class="form-group">-->
<!--        <label class="control-label col-xs-12 col-sm-2"><?php echo __('满减'); ?>:</label>-->
<!--        <div class="col-xs-12 col-sm-8">-->

<!--            <input  id="c-fullcut_switch" name="row[fullcut_switch]" type="hidden" value="<?php echo $row['fullcut_switch']; ?>">-->
<!--            <a href="javascript:;" data-toggle="switcher" class="btn-switcher" data-input-id="c-fullcut_switch" data-yes="1" data-no="0" >-->
<!--                <i class="fa fa-toggle-on text-success <?php if($row['fullcut_switch'] == '0'): ?>fa-flip-horizontal text-gray<?php endif; ?> fa-2x"></i>-->
<!--            </a>-->
<!--        </div>-->
<!--    </div>-->
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Weigh'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-weigh" data-rule="required" class="form-control" name="row[weigh]" type="number" value="<?php echo htmlentities($row['weigh']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            
            <div class="radio">
            <?php if(is_array($statusList) || $statusList instanceof \think\Collection || $statusList instanceof \think\Paginator): if( count($statusList)==0 ) : echo "" ;else: foreach($statusList as $key=>$vo): ?>
            <label for="row[status]-<?php echo $key; ?>"><input id="row[status]-<?php echo $key; ?>" name="row[status]" type="radio" value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($row['status'])?$row['status']:explode(',',$row['status']))): ?>checked<?php endif; ?> /> <?php echo $vo; ?></label> 
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>

        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>