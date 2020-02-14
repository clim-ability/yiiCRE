<?php ?>
<div class="container tmbCards">

    <div class="row">
        <?php
        $sizeSpecial = (3 == sizeof($items)) ? ' col-md-offset-1 ' : '';
        $index = -1;
        foreach ($items as $item) {
            if (!array_key_exists('hidden', $item) || !$item['hidden']) {
                $index++;
                
                $title = $item['title'];
                $description = $item['description'];
                $tooltip = null;
                // if title is to large (15) split it and move rest to description...
                if (strlen($title) > 22) {
                    $str = wordwrap($title, 22);
                    $str = explode("\n", $str);
                    $str = $str[0];
                    $pos = strpos($title, $str);
                    if ($pos !== false) {
                        $newtitle = substr_replace($title, '', $pos, strlen($str));
                        $description = $newtitle . "\n" . $description;
                    }
                    $title = $str;
                }
                if (strlen($description) > 120) {
                    $str = wordwrap($description, 117);
                    $str = explode("\n", $str);
                    $str = $str[0] . '...';
                    $tooltip = $description;
                    $description = $str;
                }
                
                echo "<div class='col-md-3 col-sm-6 " . $sizeSpecial . "'>"; //  'col-md-offset-1'  in general destroys layout for showroom
                echo "<div class=' side-box'>";
                echo "<h3>";
                echo $title;
                echo "</h3>";
                echo "<a href='" . $item['url'] . "' class='' role=''>";                  
                echo "<div class= 'thumbnail' style='min-height: 175px' >";
                if (array_key_exists('img', $item) && is_string($item['img'])) {
                    
                    echo "<img class='mydesktop-icon' src='" . $item['img'] . "'  alt='...'>";
                } else {
                    if (array_key_exists('glyphicon', $item)) {
                        echo "<p class='large glyphicon glyphicon-" . $item['glyphicon'] . "'></p>";
                    }
                }
                echo "</div>";
                echo "</a>";
                echo "<div class='caption'>";


                if (is_string($tooltip)) {
                    echo "<p class='boxed' data-toggle='tooltip' title='" . $tooltip . "'>";
                } else {
                    echo "<p class='boxed'>";
                }
                echo str_replace("\n", '<br/>', $description);
                echo "</p>";


                echo "</div>";
                echo "</div>";
                echo "</div>";
                if (3 == $index % 4) {
                    echo "</div>";
                    echo "<div class='row'>";
                }
            }
        }

        ?>  
    </div>
</div>