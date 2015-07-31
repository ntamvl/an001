<?php
$activar = get_option('activar-pelicula');
if ($activar == "true") {
?>
<div id="player-container">
<?php
    if (get_post_custom_values("embed_pelicula")) {
?>
<div class="play-c">
<?php
        if ($player = get_post_custom_values("embed_pelicula")) { ?><div id="play-1" class="player-content"><?php
            echo $player[0]; ?></div><?php
        }
?>
<?php
        if ($player = get_post_custom_values("embed_pelicula2")) { ?><div id="play-2" class="player-content"><?php
            echo $player[0]; ?></div><?php
        }
?>
<?php
        if ($player = get_post_custom_values("embed_pelicula3")) { ?><div id="play-3" class="player-content"><?php
            echo $player[0]; ?></div><?php
        }
?>
<?php
        if ($player = get_post_custom_values("embed_pelicula4")) { ?><div id="play-4" class="player-content"><?php
            echo $player[0]; ?></div><?php
        }
?>
<?php
        if ($player = get_post_custom_values("embed_pelicula5")) { ?><div id="play-5" class="player-content"><?php
            echo $player[0]; ?></div><?php
        }
?>
<?php
        if ($player = get_post_custom_values("embed_pelicula6")) { ?><div id="play-6" class="player-content"><?php
            echo $player[0]; ?></div><?php
        }
?>
<?php
        if ($player = get_post_custom_values("embed_pelicula7")) { ?><div id="play-7" class="player-content"><?php
            echo $player[0]; ?></div><?php
        }
?>
<?php
        if ($player = get_post_custom_values("embed_pelicula8")) { ?><div id="play-8" class="player-content"><?php
            echo $player[0]; ?></div><?php
        }
?>
</div>
<ul class="player-menu">
<?php
        if (get_post_custom_values("embed_pelicula")) {
?><li class="current"><a href="#play-1">
<?php
            if ($values = get_post_custom_values("titulo_repro1")) {
                echo $values[0];
            }
            else {
                echo _e('Option', 'mundothemes');
            }
?> 1
</a></li><?php
        }
?>
<?php
        if (get_post_custom_values("embed_pelicula2")) {
?><li><a href="#play-2">
<?php
            if ($values = get_post_custom_values("titulo_repro2")) {
                echo $values[0];
            }
            else {
                echo _e('Option', 'mundothemes');
            }
?> 2
</a></li><?php
        }
?>
<?php
        if (get_post_custom_values("embed_pelicula3")) {
?><li><a href="#play-3">
<?php
            if ($values = get_post_custom_values("titulo_repro3")) {
                echo $values[0];
            }
            else {
                echo _e('Option', 'mundothemes');
            }
?> 3
</a></li><?php
        }
?>
<?php
        if (get_post_custom_values("embed_pelicula4")) {
?><li><a href="#play-4">
<?php
            if ($values = get_post_custom_values("titulo_repro4")) {
                echo $values[0];
            }
            else {
                echo _e('Option', 'mundothemes');
            }
?> 4
</a></li><?php
        }
?>
<?php
        if (get_post_custom_values("embed_pelicula5")) {
?><li><a href="#play-5">
<?php
            if ($values = get_post_custom_values("titulo_repro5")) {
                echo $values[0];
            }
            else {
                echo _e('Option', 'mundothemes');
            }
?> 5
</a></li><?php
        }
?>
<?php
        if (get_post_custom_values("embed_pelicula6")) {
?><li><a href="#play-6">
<?php
            if ($values = get_post_custom_values("titulo_repro6")) {
                echo $values[0];
            }
            else {
                echo _e('Option', 'mundothemes');
            }
?> 6
</a></li><?php
        }
?>
<?php
        if (get_post_custom_values("embed_pelicula7")) {
?><li><a href="#play-7">
<?php
            if ($values = get_post_custom_values("titulo_repro7")) {
                echo $values[0];
            }
            else {
                echo _e('Option', 'mundothemes');
            }
?> 7
</a></li><?php
        }
?>
<?php
        if (get_post_custom_values("embed_pelicula8")) {
?><li><a href="#play-8">
<?php
            if ($values = get_post_custom_values("titulo_repro8")) {
                echo $values[0];
            }
            else {
                echo _e('Option', 'mundothemes');
            }
?> 8
</a></li><?php
        }
?>
</ul>
<?php
    }
    else {
?>
<div class="no_link hide">
<p><b class="icon-play-circle-outline bigtext"></b></p>
<p><?php
        if ($tex = get_option('text-38')) {
            echo $tex;
        }
        else {
            _e('No sources available', 'mundothemes');
        }
?></p>
</div>
<?php
    }
?>
</div>
<?php
}
?>
