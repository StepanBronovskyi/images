<?php?>
<img src="<?php echo $post->getImage();?>">
<br>
<p><?php echo $post->description;?></p>

Likes: <span id="like-count"><?php echo $post->getLikesCount();?></span>
<br>
<br>
<a href="#" class="btn btn-primary button-like" id="<?php echo $post->id?>">
    Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
</a>
<a href="#" class="btn btn-primary button-unlike" id="<?php echo $post->id?>">
    Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
</a>
