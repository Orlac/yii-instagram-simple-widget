<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$bgs=array();
foreach ($images as $image){
    $bgs[]='url('.$image['images']['standard_resolution']['url'].')';
}

?>
<div class="in-widget" >
<? foreach ($images as $image){ ?>
<img src="<?=$image['images']['standard_resolution']['url']; ?>" >
<? } ?>
</div>
<style>
    .in-widget{
        width: 1059px;
        height: 353px;
        margin-left: -34px;
    }
    .in-widget img{
        width: 353px;
        height: 353px;
        float: left;
    }
</style>