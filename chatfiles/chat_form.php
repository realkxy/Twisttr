<form action="" method="post" id="form_chat" onsubmit="return addChatS(this)">
<div id="name_code">
<?php
echo '<input type="hidden" name="chatroom" id="chatroom" value="'. $this->chatrooms[0]. '" />';
// if not set for logged users ($chatadd is 1) add field to set the name
// else, if $chatuser not empty, sets the field with the name, hidded
if($this->chatadd === 1) {
 echo $this->lsite['addnmcd'].': &nbsp; <em id="code_ch">'. substr(md5(date(" j-F-Y, g:i a ")), 3, 4). '</em><br />'.
  $this->lsite['name'].': <input type="text" name="chatuser" id="chatuser" size="12" maxlength="12" /> '.
  $this->lsite['code'].': <input type="text" name="cod" id="cod" size="4" maxlength="4" /> &nbsp;
  <input type="button" name="set" id="set" value="Set" onclick="setNameC(this.form)" />';
}
else if(defined('CHATUSER')) {
  echo '<input type="hidden" name="chatuser" id="chatuser" value="'. CHATUSER. '" />
   <span id="enterchat" onclick="enterChat()">'.sprintf($this->lsite['enterchat'], CHATUSER).'</span>'."<span id = 'close-chat-window-non-logged'>Close window</span>";
}

// Add or not button for links
$alink = (CHATLINK === 1) ? '<img src="chatex/url.png" alt="URL" title="Link" onclick="setUrl(\'adchat\');" />' : '';
?>
</div>
 <div id="chatadd">

  <div id="chatex">
      <span id="chat-emoticons-container" dir="ltr">
      <img src="chatex/bold.png" alt="B" onclick="addChatBIU('[b]','[/b]', 'adchat');" />
  <img src="chatex/italic.png" alt="I" onclick="addChatBIU('[i]','[/i]', 'adchat');" />
  <img src="chatex/underline.png" alt="U" onclick="addChatBIU('[u]','[/u]', 'adchat');" />
  <?php echo $alink; ?>
 &nbsp;&nbsp; 
  <img src="chatex/0.gif" alt=":)" title=":)" onclick="addSmile(':)', 'adchat');" />
  <img src="chatex/1.gif" alt=":(" title=":(" onclick="addSmile(':(', 'adchat');" />
  <img src="chatex/2.gif" alt=":P" title=":P" onclick="addSmile(':P', 'adchat');" />
  <img src="chatex/3.gif" alt=":D" title=":D" onclick="addSmile(':D', 'adchat');" />
  <img src="chatex/4.gif" alt=":S" title=":S" onclick="addSmile(':S', 'adchat');" />
  <img src="chatex/5.gif" alt=":O" title=":O" onclick="addSmile(':O', 'adchat');" />
  <img src="chatex/6.gif" alt=":=)" title=":=)" onclick="addSmile(':=)', 'adchat');" />
  <img src="chatex/7.gif" alt=":|H" title=":|H" onclick="addSmile(':|H', 'adchat');" />
  <img src="chatex/8.gif" alt=":X" title=":X" onclick="addSmile(':X', 'adchat');" />
  <img src="chatex/9.gif" alt=":-*" title=":-*" onclick="addSmile(':-*', 'adchat');" />
  </span>
 </div>
  <input type="text" name="adchat" class="chat-text" id="adchat" size="88" maxlength="200" placeholder="Write your message..."/> &nbsp;
     <button type="submit" class="chat-send-message-button btn btn-default"  id="submit"><i class="fa fa-location-arrow" id="send-chat-message-icon"></i> </button>
  <div id="logoutchat"><?php echo $this->lsite['logoutchat']; ?></div>
  <a href="http://coursesweb.net/" title="Web Programming Development Courses" target="_blank" id="mp">CoursesWeb.net</a>
 </div>
</form>