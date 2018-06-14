<?php

namespace frontend\components;



    class StringHelper {

        public function getShortNews($text) {

            return substr($text, 0, 3);

        }
    }