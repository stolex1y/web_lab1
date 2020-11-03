<?php
    function check($X, $Y, $R) {
        if (!is_int($X) || !is_float($Y) || !is_int($R)) {
            throw new TypeError();
        }
        if ($X > 0) {
            if ($Y > 0) {
                return false;
            } else {
                return (($Y >= -$R/2) && ($X <= $R));
            }
        } else {
            if ($Y > 0) {
                return $Y <= ($X + $R/2);
            } else {
                // Проверка ОДЗ
                if (($R*$R/4 - $X*$X) < 0)
                    return false;
                return $Y >= -sqrt($R*$R/4 - $X*$X);
            }
        }
    }
?>