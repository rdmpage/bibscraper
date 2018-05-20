<?php

// Gets metadata from <meta> tags

require_once (dirname(__FILE__) . '/lib.php');
require_once (dirname(__FILE__) . '/utils.php');
require_once (dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');


$urls=array(
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+contribution+to+the+taxonomy+of+the+marine+fish+genus+Argyrosomus+%28Perciformes%3A+Sciaenidae%29%2C+with+descriptions+of+two+new+species+from+southern+Africa%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+preliminary+review+of+the+Indo-Pacific+Gobiid+fishes+of+the+genus+Gnatholepis%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+review+of+the+gobioid+fishes+of+the+Maldives%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+review+of+the+Labrid+fishes+of+the+genus+Halichoeres+of+the+Western+Indian+Ocean%2C+with+descriptions+of+six+new+species%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+review+of+the+South+African+Cheilodactylid+fishes+%28Pisces%3A+Perciformes%29%2C+with+descriptions+of+two+new+species%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+review+of+the+species+of+the+genus+Neobythites+%28Pisces%3A+Ophidiidae%29+from+the+Western+Indian+Ocean%2C+with+a+description+of+seven+new+species%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+review+of+the+squirrelfishes+of+the+subfamily+Holocentrinae+from+the+Western+Indian+Ocean+and+Red+Sea%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+revised+checklist+of+the+epipelagic+and+shore+fishes+of+the+Chagos+Archipelago%2C+central+Indian+Ocean%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+revision+of+the+Zeid+fishes+%28Zeiformes%3A+Zeidae%29+of+South+Africa%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+survey+of+selected+Eastern+Cape+estuaries+with+particular+reference+to+the+ichthyofauna%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+taxonomic+study+of+the+fish+genus+Petrotilapia+%28Pisces%3A+Cichlidae%29+from+Lake+Malawi%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+taxonomic+study+of+the+Genus+Lethrinops+Regan+%28Pisces%3A+Cichlidae%29+from+Lake+Malawi%3A+part+1%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+taxonomic+study+of+the+Genus+Lethrinops+Regan+%28Pisces%3A+Cichlidae%29+from+Lake+Malawi%3A+part+2%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+taxonomic+study+of+the+Genus+Lethrinops+Regan+%28Pisces%3A+Cichlidae%29+from+Lake+Malawi%3A+part+3%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Additions+to+the+fish+fauna+of+the+Maldives+Islands%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22An+annotated+checklist+of+the+deep+demersal+fishes+of+the+Maldive+Islands%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22An+annotated+checklist+of+the+fish+fauna+of+the+River+Shire+south+of+Kapachira+Falls%2C+Malawi%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22An+annotated+checklist+of+the+species+of+the+Labroid+fish+families+Labridae+and+Scaridae%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Annotated+checklist+of+the+epipelagic+and+shore+fishes+of+the+Maldive+Islands%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Comparative+osteology+of+the+shallow+water+cardinal+fishes+%28Perciformes%3A+Apogonidae%29+with+reference+to+the+systematics+and+evolution+of+the+family%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Coral+fishes+of+the+family+Pomacentridae+from+the+Western+Indian+Ocean+and+the+Red+Sea%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Dinopercidae%2C+a+new+family+for+the+Percoid+marine+fish+genera+Dinoperca+Boulenger+and+Centrarchops+Fowler+%28Pisces%3A+Perciformes%29%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fish+community+structure+in+three+temporarily+open%2Fclosed+estuaries+on+the+Natal+coast%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+families+Blenniidae+and+Salariidae+of+the+Western+Indian+Ocean%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+families+Draconettidae+and+Callionymidae+from+the+Red+Sea+and+the+Western+Indian+Ocean%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+families+Tetrarogidae%2C+Caracanthidae+and+Synanciidae+from+the+Western+Indian+Ocean+with+further+notes+on+Scorpaenid+fishes%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+family+Anthiidae%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+family+Apogonidae+of+the+Western+Indian+Ocean+and+the+Red+Sea%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+family+Atherinidae+of+the+Red+Sea+and+the+Western+Indian+Ocean+with+a+new+freshwater+genus+and+species+from+Madagascar%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+family+Gaterinidae+of+the+Western+Indian+Ocean+and+the+Red+Sea+with+a+resume+of+all+known+Indo+Pacific+species%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+family+Gobiidae+in+South+Africa%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+family+Lethrinidae+from+the+Western+Indian+Ocean%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+family+Mullidae+in+the+Red+Sea%2C+with+a+key+to+the+species+in+the+Red+Sea+and+the+Eastern+Mediterranean%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+family+Pentacerotidae%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+family+Pseudochromidae+%28Perciformes%29+in+the+Western+Indian+Ocean+%28with+plates+1-5%29%3B+and+a+note+on+Anisochromis+Kenya%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+family+Syngnathidae+from+the+Red+Sea+and+the+Western+Indian+Ocean%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+family+Xenopoclinidae%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+Sub-family+Nasinae+with+a+synopsis+of+the+Prionurinae%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Fishes+of+the+Tristan+da+Cunha+Group+and+Gough+Island%2C+South+Atlantic+Ocean%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Gobioid+fishes+of+the+families+Gobiidae%2C+Periophthalmidae%2C+Trypauchenidae%2C+Taenioididae%2C+and+Kraemeriidae+of+the+Western+Indian+Ocean%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Hexatrygonidae%2C+a+new+family+of+stingrays+%28Myliobatiformes%3A+Batoidea%29+from+South+Africa%2C+with+comments+on+the+classification+of+Batoid+fishes%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyofaunal+characteristics+of+a+typical+temporarily+open%2Fclosed+estuary+on+the+southeast+coast+of+South+Africa%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+1%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+10%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+11%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+12%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+13%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+14%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+15%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+16%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+17%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+18%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+19%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+2%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+20%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+21%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+22%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+23%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+24%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+25%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+26%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+27%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+28%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+29%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+3%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+30%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+31%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+32%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+33%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+4%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+5%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+6%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+7%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+8%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin%3B+No.+9%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+40%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+41%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+51%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+61%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+62%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+63%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+64%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+65%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+66%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+68%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+69%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+71%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+72%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+34%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+35%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+36%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+37%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+38%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+39%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+42%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+43%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+44%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+45%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+46%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+47%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+48%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+49%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+50%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+52%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+53%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+54%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+55%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+56%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+57%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+59%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+60%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+67%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+J.L.B.+Smith+Institute+of+Ichthyology%3B+No.+70%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Ichthyological+Bulletin+of+the+JLB+Smith+Institute+of+Ichthyology%3B+No.+58%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22List+of+the+fishes+of+the+Family+Labridae+in+the+Western+Indian+Ocean%2C+with+new+records+and+five+new+species%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Neotype+description+for+the+African+catfish+Clarias+Gariepinus+%28Burchell%2C+1822%29+%28Pisces%3A+Siluroidei%3A+Clariidae%29%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22New+records+of+fishes+from+the+Maldive+Islands%2C+with+notes+on+other+species%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22New+species+and+new+records+of+rare+Antarctic+Paraliparis+fishes+%28Scorpaeniformes%3A+Liparididae%29%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Recruitment+of+juvenile+marine+fishes+into+permanently+open+and+seasonally+open+estuarine+systems+on+the+southern+coast+of+South+Africa%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Review+of+the+deep-sea+anglerfishes+%28Lophiiformes%3A+Ceratioidei%29+of+southern+Africa%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Review+of+the+grenadiers+%28Teleostei%3A+Gadiformes%29+of+Southern+Africa%2C+with+descriptions+of+four+new+species%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Review+of+the+Indo-Pacific+pipefish+genus+Doryrhamphus+Kaup+%28Pisces%3A+Syngnathidae%29+with+descriptions+of+a+new+species+and+a+new+subspecies%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Sand-dwelling+eels+of+the+Western+Indian+Ocean+and+the+Red+Sea%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Serioline+fishes+%28yellowtails+%3A+amberjacks%29+from+the+Western+Indian+Ocean%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Serranid+fishes+of+Tanzania+and+Kenya%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Sharks+of+the+Genus+Isurus+Rafinesque%2C+1810%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Sharks+of+the+genus+Pterolamiops+Springer%2C+1951+with+notes+on+the+Isurid+sharks%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Stages+in+the+early+development+of+40+marine+fish+species+with+pelagic+eggs+from+the+Cape+of+Good+Hope%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Studies+on+the+Zoarcidae+%28Teleostei%3A+Perciformes%29+of+the+Southern+Hemisphere+IV.+New+records+and+a+new+species+from+the+Magellan+Province+of+South+America%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Swordfish%2C+marlins+and+sailfish+in+South+and+East+Africa%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Systematics+and+Osteology+of+the+Zoarcidae+%28Teleostei%3A+Perciformes%29%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+clingfishes+of+the+Western+Indian+Ocean+and+the+Red+Sea%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+Congrid+eels+of+the+Western+Indian+Ocean+and+the+Red+Sea%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+fishes+of+the+family+Eleotridae+in+the+Western+Indian+Ocean%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+fishes+of+the+Family+Scorpaenidae%3B+Part+1%3A+The+sub-family+Scorpaeninae%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+fishes+of+the+Family+Scorpaenidae%3B+Part+2%3A+The+sub-families+Pteroinae%2C+Apistinae%2C+Setarchinae+and+Sebastinae%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+fishes+of+the+Family+Sphyraenidae+in+the+Western+Indian+Ocean%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+fishes+of+the+Okavango+drainage+system+in+Angola%2C+South+West+Africa+and+Botswana%3A+taxonomy+and+distribution%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+Gunnellichthid+Fishes+with+description+of+two+new+species+from+East+Africa+and+of+Gunnellichthys+%28Clarkichthys%29+Bilineatus+%28Clark%29%2C+1936%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+identity+of+Scarus+gibbus+Ruppell%2C+1828+and+of+other+parrotfishes+of+the+family+Callyodontidae+from+the+Red+Sea+and+the+Western+Indian+Ocean%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+Labrid+Fishes+of+the+Subgenus+Julis+Cuvier%2C+1814+%28In+CORIS+Lacepede%2C+1802%29%2C+from+South+and+East+Africa%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+moray+eels+of+the+Western+Indian+Ocean+and+the+Red+Sea%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+nature+of+the+barriers+separating+the+Lake+Malawi+and+Zambezi+fish+faunas%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+Parrot+fishes+of+the+Family+Callyodontidae+of+the+Western+Indian+Ocean%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+parrotfishes+of+the+subfamily+Scarinae+of+the+Western+Indian+Ocean+with+descriptions+of+three+new+species%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+rare+%5C%22Furred-Tongue%5C%22+Uraspis+uraspis+%28Gunther%29+from+South+Africa%2C+and+other+new+records+from+there%22&collection=vital%3A91',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+taxonomic+status+and+phylogenetic+relationship+of+Pseudocrenilabrus+Fowler+%28Teleostei%2C+Cichilidae%29%22&collection=vital%3A91'
);


$urls=array(
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22A+new+squalid+Shark+from+South+Africa+with+notes+on+the+rare+Atractophorus+Armatus+Gilchrist%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Certain+rare+fishes+from+South+Africa%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Hitherto+unknown+early+developmental+larval+stadia+of+the+West+African+albulid+fish+Pterothrissus+Belloci+Cadenat%2C+1937%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Interesting+fishes+from+South+Africa%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22J.L.B.+Smith%3A+his+life%2C+work%2C+bibliography+and+list+of+new+species%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22J.L.B.+Smith%3A+his+life+and+work%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22J.L.B.+Smith%3A+sy+lewe+en+werk%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Kaupichthys+Diodontus+Schultz+in+the+western+Indian+Ocean%3A+a+problem+in+systematics%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22New+records+and+descriptions+of+fishes+from+Southwest+Africa%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22New+records+and+new+species+of+fishes+from+South+Africa%2C+chiefly+from+Natal%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Studies+in+carangid+fishes+no.+1%3A+naked+thoracic+areas%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Studies+in+carangid+fishes+no.+2%3A+the+identity+of+Scomber+Malabaricus+Bloch-Schneider%2C+1801%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Studies+in+carangid+fishes+no.+4%3A+the+identity+of+Scomber+Sansun+Forsskal%2C+1775%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Studies+in+carangid+fishes+no.3%3A+the+genus+Trachinotus+Lacepede%2C+in+the+western+Indian+Ocean%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Studies+in+Carangid+fishes+no+5%3A+the+genus+Chorinemus+%28Cuvier%2C+1831%29+in+the+western+Indian+Ocean%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22Studies+in+Carangid+fishes+no+6%3A+key+to+western+Indian+Ocean+species+of+the+genus+Carangoides+Bleeker%2C+1851%2C+with+a+desciption+of+Carangoides+Nitidus+Smith%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+Indian+genus+Bathymyrus+Alcock%2C+1889+with+description+of+a+new+species+from+Vietnam%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+life+and+work+of+Margaret+M.+Smith%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+lizard+shark+Chlamydoselachus+Anguineus+Garman%2C+in+South+Africa%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+rare+big-eye+Pristigenys+Niphonia+%28C%26V%29+in+South+Africa%22&collection=vital%3A92',
'http://vital.seals.ac.za:8080/vital/access/manager/Repository?exact=sm_title:%22The+Statute+of+Limitation+-+stability+or+chaos%22&collection=vital%3A92'
);


	foreach ($urls as $url)
	{

		$html = get($url);
		
		if ($html != '')
		{

		$reference = new stdclass;
		$reference->authors = array();
		//$reference->id = $id;


		$dom = str_get_html($html);

		$metas = $dom->find('meta');

		
		foreach ($metas as $meta)
		{
			// echo $meta->name . " " . $meta->content . "\n";
		}
					

		foreach ($metas as $meta)
		{
			switch ($meta->name)
			{

				// DC

				case 'DC.title':
				case 'dc.Title':
					$reference->title =  $meta->content;
					$reference->title = preg_replace('/\s\s+/u', ' ', $reference->title);
					break;
					
				case 'DC.identifier':
					if (preg_match('/http:\/\/hdl.handle.net\/(?<id>.*)/', $meta->content, $m))
					{
						$reference->handle = $m['id'];
					}
					break;

				/*
				case 'DC.description':
				case 'DC.Description':
				case 'citation_abstract':
					$reference->abstract =  $meta->content;
					$reference->abstract = str_replace("\n", "", $reference->abstract);
					$reference->abstract = str_replace("&amp;", "&", $reference->abstract);
					$reference->abstract = preg_replace('/\s\s+/u', ' ', $reference->abstract);		
					$reference->abstract = preg_replace('/^\s+/u', '', $reference->abstract);		
					$reference->abstract = html_entity_decode($reference->abstract);
					break;
				*/
					
				case 'DCTERMS.abstract':
					$reference->abstract =  $meta->content;
					break;
			
				case 'DC.Creator.PersonalName':
					if (!in_array($meta->content, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;	
					
				case 'DC.creator':
					if (!in_array($meta->content, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;	
					
				case 'DCTERMS.issued':
					if (preg_match('/[0-9]{4}-[0-9]{2}/', $meta->content))
					{
						$reference->date = $meta->content . '-00';
					}
					else
					{
						$reference->date = $meta->content;
					}
					break;
					
				case 'DC.relation':
					if (preg_match('/(?<journal>The Bulletin of the Texas Memorial Museum);\s+no\.\s+(?<volume>\d+)/', $meta->content, $m))
					{
						$reference->journal = $m['journal'];
						$reference->volume = $m['volume'];
					}
					break;
			
				case 'DC.Source.ISSN':
					$reference->issn =  $meta->content;
					break;	

				// eprints

				case 'eprints.creators_name':
					$author = $meta->content;

					// clean
					if (preg_match('/^(?<lastname>.*),\s+(?<firstname>[A-Z][A-Z]+)$/u', $author, $m))
					{
						$parts = str_split($m['firstname']);
						$author = $m['lastname'] . ', ' . join(". ", $parts) . '.';
					}
					if (!in_array($author, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;

				case 'eprints.publication':
					$reference->journal =  $meta->content;
					break;

				case 'eprints.issn':
					$reference->issn =  $meta->content;
					break;


				case 'eprints.volume':
					$reference->volume =  $meta->content;
					break;

				case 'eprints.pagerange':
					$pages =  $meta->content;
					$parts = explode("-", $pages);
					if (count($parts) > 1)
					{
						$reference->spage = $parts[0];
						$reference->epage = $parts[1];
					}
					else
					{
						$reference->spage = $pages;
					}
					break;

				case 'eprints.date':
					if (preg_match('/^[0-9]{4}$/', $meta->content))
					{
						$reference->year = $meta->content;
					}

					if (preg_match('/^(?<year>[0-9]{4})\//', $meta->content, $m))
					{
						$reference->year = $m['year'];
					}
					break;

				case 'eprints.document_url':
					$reference->pdf =  urldecode($meta->content);
					break;

				// Google	
				case 'citation_authors':
					$authorstring = $meta->content;
					$authorstring = preg_replace('/,\s+/', '|', $authorstring);
					$authorstring = preg_replace('/\s+&\s+/', '|', $authorstring);
					$reference->authors = explode("|", $authorstring);
					break;
					
				case 'citation_author':
					if (!in_array($meta->content, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;

				case 'citation_title':
					$reference->title = trim($meta->content);
					$reference->title = html_entity_decode($reference->title);
					$reference->title = preg_replace('/\s\s+/u', ' ', $reference->title);
					break;

				case 'citation_doi':
					$reference->doi =  $meta->content;
					break;

				case 'citation_journal_title':
					$reference->journal =  $meta->content;
					$reference->genre = 'article';
					break;

				case 'citation_issn':
					if (!isset($reference->issn))
					{
						$reference->issn =  $meta->content;
					}
					break;

				case 'citation_volume':
					$reference->volume =  $meta->content;
					break;

				case 'citation_issue':
					$reference->issue =  $meta->content;
					break;

				case 'citation_firstpage':
					$reference->spage =  $meta->content;
			
					if (preg_match('/(?<spage>\d+)[-|-](?<epage>\d+)/u', $meta->content, $m))
					{
						$reference->spage =  $m['spage'];
						$reference->epage =  $m['epage'];
					}
					break;

				case 'citation_lastpage':
					$reference->epage =  $meta->content;
					break;

				case 'citation_abstract_html_url':
					$reference->url =  $meta->content;
					break;

				case 'citation_pdf_url':
					$reference->pdf =  $meta->content;
					
					if (preg_match('/vital:(?<id>\d+);/', $reference->pdf, $m))
					{
						$reference->id = 'vital:' . $m['id'];
					}
					
					$reference->pdf = preg_replace('/;jsessionid=[0-9A-Z]+\/SOURCEPDF/', '/SOURCEPDF', $reference->pdf);
					$reference->pdf = str_replace('manager/Repository', 'services/Download', $reference->pdf);
					
					break;
			
				case 'citation_fulltext_html_url':
					$reference->pdf =  $meta->content;
					//$reference->pdf = str_replace('/view/', '/download/', $reference->pdf);
					break;
			

				case 'citation_date':
				case 'citation_publication_date':
					if (preg_match('/^[0-9]{4}$/', $meta->content))
					{
						$reference->year = $meta->content;
					}

					if (preg_match('/^(?<year>[0-9]{4})\//', $meta->content, $m))
					{
						$reference->year = $m['year'];
					}
					
					if (preg_match('/^[0-9]{4}-[0-9]{2}$/', $meta->content, $m))
					{
						$reference->date = $meta->content . '-00';
					}
					if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $meta->content, $m))
					{
						$reference->date = $meta->content . '-00';
					}
					
					break;

				/*
				case 'DC.Date':
				case 'dc.Date':
					$reference->date = $meta->content;
					break;
				*/

				default:
					break;
			}
		}		
		//print_r($reference);
		
		// get MODS XML
		if (isset($reference->id))
		{
			$url = 'http://vital.seals.ac.za:8080/vital/access/services/Download/' . $reference->id . '/MODS';
			
			$xml = get($url);
			
			//echo $xml;
			
			$dom = new DOMDocument;
			$dom->loadXML($xml);
			$xpath = new DOMXPath($dom);
	
			$xpath->registerNamespace('mods', "http://www.loc.gov/mods/v3");
	
	
			$nodeCollection = $xpath->query ('//mods:identifier');
			foreach($nodeCollection as $node)
			{
				//echo $node->firstChild->nodeValue . "\n";
		
				if (preg_match('/http:\/\/hdl.handle.net\/(?<handle>.*)/', $node->firstChild->nodeValue , $m))
				{
					$reference->handle = $m['handle'];
					$reference->url = $node->firstChild->nodeValue;
				}
		
				if (preg_match('/ISSN\s+(?<issn>.*)/', $node->firstChild->nodeValue , $m))
				{
					$reference->issn = $m['issn'];
				}
		
			}
	
			/*
			$nodeCollection = $xpath->query ('//mods:name/mods:namePart');
			foreach($nodeCollection as $node)
			{
				$reference->authors[] = $node->firstChild->nodeValue;
			}
			*/
	
			$nodeCollection = $xpath->query ('//mods:physicalDescription/mods:extent');
			foreach($nodeCollection as $node)
			{
				if (preg_match('/(?<spage>\d+)-(?<epage>\d+)/',$node->firstChild->nodeValue,$m))
				{
					$reference->spage = $m['spage'];
					$reference->epage = $m['epage'];
				}
			
			}
			
			$nodeCollection = $xpath->query ('//mods:titleInfo[@type="alternative"]/mods:title');
			foreach($nodeCollection as $node)
			{
				//echo $node->firstChild->nodeValue . "\n";
			
				if (preg_match('/(?<journal>.*)[;|:]\s+No.\s+(?<volume>\d+)/',$node->firstChild->nodeValue,$m))
				{
					$reference->journal = $m['journal'];
					$reference->volume = $m['volume'];
				}
			
			}
			
			$nodeCollection = $xpath->query ('//mods:titleInfo/mods:title');
			foreach($nodeCollection as $node)
			{
				//echo $node->firstChild->nodeValue . "\n";
			
				if (preg_match('/(?<journal>.*)[;|:]\s+No.\s+(?<volume>\d+)/',$node->firstChild->nodeValue,$m))
				{
					$reference->journal = $m['journal'];
					$reference->volume = $m['volume'];
				}
			
			}
			
			
			$reference->abstract = '';
			$nodeCollection = $xpath->query ('//mods:abstract');
			foreach($nodeCollection as $node)
			{
				$reference->abstract.= $node->firstChild->nodeValue;
			}
			if ($reference->abstract == '')
			{
				unset($reference->abstract);
			}
			
		
			
				
		}
		


		//print_r($reference);
		
		//echo 'UPDATE publications SET authors="' . join(";", $reference->authors) . '" WHERE guid="' . $reference->handle . '";' . "\n";

		echo reference_to_ris($reference);

		// Give server a break every 10 items
		if (($count++ % 10) == 0)
		{
			$rand = rand(1000000, 3000000);
			echo "\n...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
			usleep($rand);
		}
		
	}
	
}


?>