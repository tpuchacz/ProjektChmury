<?php
if(session_status() === PHP_SESSION_NONE)
        session_start();
        function DisplayPreview($tytulPre, $streszczeniePre, $trescPre){
            $znaczniki = array(
                '[b]' => '<b>',
                '[/b]' => '</b>',
                '[u]' => '<u>',
                '[/u]'  => '</u>',
                '[i]' => '<i>',
                '[/i]'  => '</i>',
                '[s]' => '<sub>',
                '[/s]'  => '</sub>'
            );
            $find = array_keys($znaczniki);
            $replace = array_values($znaczniki);
            $tytulPost = str_ireplace($find, $replace, $tytulPre);
            $streszczeniePost = str_ireplace($find, $replace, $streszczeniePre);
            $trescPost = str_ireplace($find, $replace, $trescPre);
            echo "<p id='preview'>Tytuł: ".$tytulPost."<br>";
            echo "Streszczenie: ".$streszczeniePost."<br>";
            echo "Tresc:<br>".$trescPost."</p><br>";
        }
