<?php

function loremPiece()
{
    $sentences = [["滾到了天半，","漸被遮蔽，","濛迷地濃結起來，","無奈群眾的心裡，","熱血正在沸騰，","燈火星星地，","現在不高興了，","現在只有覺悟，","現在想沒得再一個，","現在的我，","生性如此，","生的糧食儘管豐富，","由我的生的行程中，","由深藍色的山頭，","由著裊裊的晚風，","由隘巷中走出來，","甲微喟的說，","甲憤憤地罵，","甲興奮地說，","略一對比，","當科白尼還未出世，","看我們現在，","看見有幾次的變遷，","看見鮮紅的血，","眩眼一縷的光明，","礙步的石頭，","福戶內的事，","禮義之邦的中國，","究竟為的是什麼，","那些富家人，","那時代的一年，","那更不成問題，","那末地球運行最初，","那邊亭仔腳幾人，","那邊有些人，","都很讚成，","金錢愈不能到手，","金錢的問題，","金錢的慾念，","銅鑼響亮地敲起來，","錢的可能性愈少，","鑼的響聲，","鑼聲亦不響了，","阻斷爭論，","除廢掉舊曆，","陷人的泥澤，","雖亦有人反對，","雖則不知，","雖受過欺負，","雖未見到結論，","雖遇有些顛蹶，","雨太太的好意，","音響的餘波，","風雨又調和著節奏，","驟然受到光的刺激，","體軀支持不住了，","鬧熱到了，","鬧過別一邊去，"],["一層層堆聚起來。","不停地前進。","不可讓他佔便宜。","不教臉紅而已。","不知橫亙到何處。","丙可憐似的說。","也須為著子孫鬥爭。","互相提攜走向前去。","亦不算壞。","人類的一分子了。","今夜是明月的良宵。","他正在發瘋呢。","那邊比較鬧熱。","險些兒跌倒。","黃金難買少年心。"],["一樣是歹命人！","但是這一番啊！","來--來！","來和他們一拚！","值得說什麼爭麵皮！","兄弟們來！","到城裡去啊！","又受了他們一頓罵！","和他們一拚！","實在想不到！","憑這一身！","憑這雙腕！","我要頂禮他啊！","把我們龍頭割去！","捨此一身和他一拚！","明夜沒得再看啦！","歲月真容易過！","比狗還輸！","無目的地前進！","甘失掉了麵皮！","盲目地前進！","老不死的混蛋！","趕快走下山去！","這是如何地悲悽！","這是如何的決意！","那纔利害啦！"]];
    $out = '';
    while(rand(0,1) == 1) {
      $out .= $sentences[0][array_rand($sentences[0])];
    }
    $rand = 1 + rand(0,1);
    $out .= $sentences[$rand][array_rand($sentences[$rand])];
    return $out;
}

function lorem($total = 20)
{
    $out = [];
    while(count($out) < $total) {
      array_push($out, loremPiece());
    }
    return join('', $out);
}
