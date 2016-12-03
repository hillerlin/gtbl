<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">
    <div style="margin:15px auto 0; width:1000px;">
        <fieldset>
            <legend>文本框</legend>
            <h3 class="page-header"><?php echo ($title); ?></h3>
            <?php echo htmlspecialchars_decode($content)?>
        </fieldset>
    </div>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
    </ul>
</div>