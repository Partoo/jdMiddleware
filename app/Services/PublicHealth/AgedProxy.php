<?php

namespace App\Services\PublicHealth;

/**
 * 老年人
 */
class AgedProxy extends PublicHealthProxy
{

    public function getParams()
    {
        // 老年人年龄为65岁
        $agedBoundary = date('Y', strtotime('-65 years')) . '-12-31';
        return '&birstarttime=1900-01-01&birendtime=' . $agedBoundary . '&dSfzh=&sfhg=&happenstarttime=&happenendtime=&dDazt=&selectChange=&updatestarttime=&updateendtime=&sfzdgl=&ssjd=&ssjwh=&ssxxdz=&tjqk=&jsbtjqk=&gxytjqk=&tnbtjqk=&lnrzlnl=&hb=hb&_hb=on&wbc=wbc&_wbc=on&plt=plt&_plt=on&_gXcgqt=on&gNdb=gNdb&_gNdb=on&gNt=gNt&_gNt=on&gNtt=gNtt&_gNtt=on&gNqx=gNqx&_gNqx=on&alt=alt&_alt=on&ast=ast&_ast=on&tbil=tbil&_tbil=on&scr=scr&_scr=on&bun=bun&_bun=on&cho=cho&_cho=on&tg=tg&_tg=on&ldlc=ldlc&_ldlc=on&hdlc=hdlc&_hdlc=on&gKfxt=gKfxt&_gKfxt=on&gXindt=gXindt&_gXindt=on&gBchao=gBchao&_gBchao=on&all1=on';
    }
}