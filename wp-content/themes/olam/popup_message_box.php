<?
$notifications = getMessages(1);
$messages = getMessages();

$messagesEmpty = count($messages) == 0? "empty" : "";
$notificationsEmpty = count($notifications) == 0? "empty" : "";

$newMessage = count($messages) + count($notifications);

if ($newMessage > 0){
    echo "<script>jQuery('.elMes').addClass('newMessage');</script>";
}

?>
<div class="message_popup">
    <div id="message_tabs">
    <ul>
        <li><a href="#fragment-1">Уведомления</a></li>
        <li><a href="#fragment-2">Сообщения</a></li>
    </ul>
    <div id="fragment-1" class="noScroll <?echo $notificationsEmpty?>">
    <? if ($notificationsEmpty == "empty") echo "Новых уведомлений нет";?>
        <? foreach($notifications as $item) : ?>
        <?
            if ($item->message_status == 0){
            $user = get_user_by("ID", $item->user_id);
            ?>
            <a href="#">
                <div class="mes_main notify" data-id="<?=$item->user_id?>">
                    <div class="popup_mes_avatar"><img src="<? echo get_avatar_url( $item->user_id )?>"></div>
                    <div class="mes_author"><? echo $user->display_name == null ? $user->user_login : $user->display_name ?></div>
                    <div class="mes_message"><? echo $item->message_content ?></div>
                </div>
            </a>
            <?
            }
            ?>
        <? endforeach ?>

    </div>
    
    <div id="fragment-2" class="noScroll <?echo $messagesEmpty?>">
    <? if ($messagesEmpty == "empty") echo "Новых сообщений нет";?>    
        <? foreach($messages as $item) : ?>
            <?
            if ($item->message_status == 0){
            $user = get_user_by("ID", $item->user_id);
            ?>
            <a href="<?=the_user_chat_link( $item->user_id )?>">
                <div class="mes_main" data-id="<?=$item->user_id?>">
                    <div class="popup_mes_avatar"><img src="<? echo get_avatar_url( $item->user_id )?>"></div>
                    <div class="mes_author"><? echo $user->display_name == null ? $user->user_login : $user->display_name ?></div>
                    <div class="mes_message"><? echo $item->message_content ?></div>
                </div>
            </a>
            <?}?>
        <? endforeach ?>

    </div>

    <div class="mes_footer">
        <a href="<? echo get_site_url(null, 'vendor-dashboard/?task=products'); ?>" style="float: left;">Мои заказы</a>
        <a href="<? echo get_site_url(null, 'messages');?>" style="float: right;">Открыть диалоги</a></div>
    </div>
</div>
<script>
            jQuery("#message_tabs").tabs();
</script>