


<!--

Follow me on
Dribbble: https://dribbble.com/supahfunk
Twitter: https://twitter.com/supahfunk
Codepen: https://codepen.io/supah/

It's just a concept, a fake chat to design a new daily UI for direct messaging.
Hope you like it :)

-->

<div class="chat" style="display: none;">
    <div class="chat-title">
        <h1><?php echo $HomePage->WebsiteDetails->SiteName; ?></h1>
        <h2 id="game-words">Mandate , Nigeria</h2>
        <figure class="avatar">
            <img src="<?php echo $HomePage->WebsiteDetails->IMG_FOLDER.'favicon.png'; ?>" /></figure>
    </div>
    <div class="messages">
        <div class="messages-content"></div>
    </div>
    <div class="message-box">
        <textarea type="text" class="message-input" placeholder="Type word..."></textarea>
        <button type="submit" class="message-submit"><i class="fa fa-send"></i> </button>
    </div>

</div>
<div class="bg"></div>

