<?php

// Get abstract from web page

require_once (dirname(__FILE__) . '/lib.php');
require_once (dirname(__FILE__) . '/utils.php');
require_once (dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');

$dois = array(

'10.24199/j.mmv.1984.45.03',
'10.24199/j.mmv.1984.45.01',
'10.24199/j.mmv.1984.45.05',
'10.24199/j.mmv.1984.45.04',
'10.24199/j.mmv.1984.45.02',
'10.24199/j.mmv.1985.46.02',
'10.24199/j.mmv.1985.46.04',
'10.24199/j.mmv.1985.46.03',
'10.24199/j.mmv.1985.46.05',
'10.24199/j.mmv.1985.46.06',
'10.24199/j.mmv.1985.46.01',
'10.24199/j.mmv.1986.47.04',
'10.24199/j.mmv.1986.47.03',
'10.24199/j.mmv.1986.47.01',
'10.24199/j.mmv.1986.47.02',
'10.24199/j.mmv.1986.47.06',
'10.24199/j.mmv.1986.47.07',
'10.24199/j.mmv.1986.47.08',
'10.24199/j.mmv.1986.47.05',
'10.24199/j.mmv.1987.48.20',
'10.24199/j.mmv.1987.48.03',
'10.24199/j.mmv.1987.48.01',
'10.24199/j.mmv.1987.48.04',
'10.24199/j.mmv.1987.48.05',
'10.24199/j.mmv.1987.48.02',
'10.24199/j.mmv.1987.48.06',
'10.24199/j.mmv.1987.48.07',
'10.24199/j.mmv.1987.48.08',
'10.24199/j.mmv.1987.48.09',
'10.24199/j.mmv.1987.48.11',
'10.24199/j.mmv.1987.48.12',
'10.24199/j.mmv.1987.48.13',
'10.24199/j.mmv.1987.48.10',
'10.24199/j.mmv.1987.48.14',
'10.24199/j.mmv.1987.48.15',
'10.24199/j.mmv.1987.48.17',
'10.24199/j.mmv.1987.48.18',
'10.24199/j.mmv.1987.48.19',
'10.24199/j.mmv.1987.48.16',
'10.24199/j.mmv.1988.49.11',
'10.24199/j.mmv.1988.49.08',
'10.24199/j.mmv.1988.49.01',
'10.24199/j.mmv.1988.49.03',
'10.24199/j.mmv.1988.49.02',
'10.24199/j.mmv.1988.49.06',
'10.24199/j.mmv.1988.49.04',
'10.24199/j.mmv.1988.49.05',
'10.24199/j.mmv.1988.49.07',
'10.24199/j.mmv.1988.49.10',
'10.24199/j.mmv.1989.50.01',
'10.24199/j.mmv.1990.50.11',
'10.24199/j.mmv.1990.51.02',
'10.24199/j.mmv.1990.51.06',
'10.24199/j.mmv.1990.51.08',
'10.24199/j.mmv.1990.50.02',
'10.24199/j.mmv.1990.50.04',
'10.24199/j.mmv.1990.51.03',
'10.24199/j.mmv.1990.51.05',
'10.24199/j.mmv.1990.51.07',
'10.24199/j.mmv.1990.51.09',
'10.24199/j.mmv.1990.50.03',
'10.24199/j.mmv.1990.50.06',
'10.24199/j.mmv.1990.50.07',
'10.24199/j.mmv.1990.50.08',
'10.24199/j.mmv.1990.50.10',
'10.24199/j.mmv.1990.50.09',
'10.24199/j.mmv.1990.50.12',
'10.24199/j.mmv.1990.50.14',
'10.24199/j.mmv.1990.50.15',
'10.24199/j.mmv.1990.50.16',
'10.24199/j.mmv.1991.52.03',
'10.24199/j.mmv.1991.52.11',
'10.24199/j.mmv.1991.52.10',
'10.24199/j.mmv.1991.52.08',
'10.24199/j.mmv.1991.52.13',
'10.24199/j.mmv.1991.52.01',
'10.24199/j.mmv.1991.52.02',
'10.24199/j.mmv.1991.52.05',
'10.24199/j.mmv.1991.52.04',
'10.24199/j.mmv.1991.52.06',
'10.24199/j.mmv.1991.52.07',
'10.24199/j.mmv.1991.52.09',
'10.24199/j.mmv.1991.52.12',
'10.24199/j.mmv.1992.53.06',
'10.24199/j.mmv.1992.53.01',
'10.24199/j.mmv.1992.53.03',
'10.24199/j.mmv.1992.53.04',
'10.24199/j.mmv.1992.53.02',
'10.24199/j.mmv.1992.53.05',
'10.24199/j.mmv.1992.53.07',
'10.24199/j.mmv.1992.53.09',
'10.24199/j.mmv.1992.53.10',
'10.24199/j.mmv.1992.53.08',
'10.24199/j.mmv.1994.54.10',
'10.24199/j.mmv.1994.54.07',
'10.24199/j.mmv.1994.54.05',
'10.24199/j.mmv.1994.54.12',
'10.24199/j.mmv.1994.54.11',
'10.24199/j.mmv.1994.54.09',
'10.24199/j.mmv.1994.54.13',
'10.24199/j.mmv.1994.54.14',
'10.24199/j.mmv.1994.54.16',
'10.24199/j.mmv.1994.54.15',
'10.24199/j.mmv.1994.54.04',
'10.24199/j.mmv.1994.54.02',
'10.24199/j.mmv.1994.54.08',
'10.24199/j.mmv.1994.54.06',
'10.24199/j.mmv.1994.54.01',
'10.24199/j.mmv.1994.54.03',
'10.24199/j.mmv.1995.55.02',
'10.24199/j.mmv.1995.55.01',
'10.24199/j.mmv.1997.56.06',
'10.24199/j.mmv.1997.56.67',
'10.24199/j.mmv.1997.56.65',
'10.24199/j.mmv.1997.56.61',
'10.24199/j.mmv.1997.56.50',
'10.24199/j.mmv.1997.56.57',
'10.24199/j.mmv.1997.56.59',
'10.24199/j.mmv.1997.56.46',
'10.24199/j.mmv.1997.56.60',
'10.24199/j.mmv.1997.56.01',
'10.24199/j.mmv.1997.56.02',
'10.24199/j.mmv.1997.56.03',
'10.24199/j.mmv.1997.56.04',
'10.24199/j.mmv.1997.56.05',
'10.24199/j.mmv.1997.56.07',
'10.24199/j.mmv.1997.56.09',
'10.24199/j.mmv.1997.56.08',
'10.24199/j.mmv.1997.56.11',
'10.24199/j.mmv.1997.56.12',
'10.24199/j.mmv.1997.56.14',
'10.24199/j.mmv.1997.56.42',
'10.24199/j.mmv.1997.56.41',
'10.24199/j.mmv.1997.56.40',
'10.24199/j.mmv.1997.56.43',
'10.24199/j.mmv.1997.56.44',
'10.24199/j.mmv.1997.56.45',
'10.24199/j.mmv.1997.56.48',
'10.24199/j.mmv.1997.56.51',
'10.24199/j.mmv.1997.56.52',
'10.24199/j.mmv.1997.56.49',
'10.24199/j.mmv.1997.56.53',
'10.24199/j.mmv.1997.56.54',
'10.24199/j.mmv.1997.56.55',
'10.24199/j.mmv.1997.56.56',
'10.24199/j.mmv.1997.56.63',
'10.24199/j.mmv.1997.56.64',
'10.24199/j.mmv.1997.56.62',
'10.24199/j.mmv.1997.56.69',
'10.24199/j.mmv.1997.56.66',
'10.24199/j.mmv.1997.56.68',
'10.24199/j.mmv.1997.56.30',
'10.24199/j.mmv.1997.56.33',
'10.24199/j.mmv.1997.56.28',
'10.24199/j.mmv.1997.56.29',
'10.24199/j.mmv.1997.56.32',
'10.24199/j.mmv.1997.56.34',
'10.24199/j.mmv.1997.56.36',
'10.24199/j.mmv.1997.56.38',
'10.24199/j.mmv.1997.56.37',
'10.24199/j.mmv.1997.56.35',
'10.24199/j.mmv.1998.57.08',
'10.24199/j.mmv.1998.57.05',
'10.24199/j.mmv.1998.57.07',
'10.24199/j.mmv.1998.57.01',
'10.24199/j.mmv.1998.57.04',
'10.24199/j.mmv.1998.57.03',
'10.24199/j.mmv.1998.57.02',
'10.24199/j.mmv.1998.57.06',
'10.24199/j.mmv.1992.53.13',
'10.24199/j.mmv.1992.53.11',
'10.24199/j.mmv.1992.53.12',
'10.24199/j.mmv.1992.53.14',
'10.24199/j.mmv.1992.53.15',
'10.24199/j.mmv.1997.56.58',
'10.24199/j.mmv.1997.56.23',
'10.24199/j.mmv.1997.56.16',
'10.24199/j.mmv.1997.56.15',
'10.24199/j.mmv.1997.56.17',
'10.24199/j.mmv.1997.56.20',
'10.24199/j.mmv.1997.56.18',
'10.24199/j.mmv.1997.56.24',
'10.24199/j.mmv.1997.56.25',
'10.24199/j.mmv.1997.56.27',
'10.24199/j.mmv.1997.56.26',
'10.24199/j.mmv.1997.56.31',

'10.24199/j.mmv.2000.58.1',
'10.24199/j.mmv.2000.58.2',
'10.24199/j.mmv.2000.58.7',
'10.24199/j.mmv.2000.58.8',
'10.24199/j.mmv.2000.58.6',
'10.24199/j.mmv.2000.58.4',
'10.24199/j.mmv.2000.58.5',
'10.24199/j.mmv.2000.58.3',
'10.24199/j.mmv.2001.58.10',
'10.24199/j.mmv.2001.58.16',
'10.24199/j.mmv.2001.59.1',
'10.24199/j.mmv.2001.58.11',
'10.24199/j.mmv.2001.58.14',
'10.24199/j.mmv.2001.58.15',
'10.24199/j.mmv.2001.58.17',
'10.24199/j.mmv.2001.58.18',
'10.24199/j.mmv.2001.58.13',
'10.24199/j.mmv.2001.58.12',
'10.24199/j.mmv.2001.58.19',
'10.24199/j.mmv.2001.58.20',
'10.24199/j.mmv.2001.58.9',
'10.24199/j.mmv.2001.58.21',
'10.24199/j.mmv.2002.59.2',
'10.24199/j.mmv.2002.59.6',
'10.24199/j.mmv.2002.59.5',
'10.24199/j.mmv.2002.59.9',
'10.24199/j.mmv.2002.59.12',
'10.24199/j.mmv.2002.59.10',
'10.24199/j.mmv.2002.59.3',
'10.24199/j.mmv.2002.59.11',
'10.24199/j.mmv.2002.59.4',
'10.24199/j.mmv.2002.59.7',
'10.24199/j.mmv.2002.59.8',
'10.24199/j.mmv.2003.60.14',
'10.24199/j.mmv.2003.60.17',
'10.24199/j.mmv.2003.60.15',
'10.24199/j.mmv.2003.60.18',
'10.24199/j.mmv.2003.60.16',
'10.24199/j.mmv.2003.60.2',
'10.24199/j.mmv.2003.60.20',
'10.24199/j.mmv.2003.60.22',
'10.24199/j.mmv.2003.60.19',
'10.24199/j.mmv.2003.60.23',
'10.24199/j.mmv.2003.60.7',
'10.24199/j.mmv.2003.60.9',
'10.24199/j.mmv.2003.60.8',
'10.24199/j.mmv.2003.60.6',
'10.24199/j.mmv.2003.60.10',
'10.24199/j.mmv.2003.60.13',
'10.24199/j.mmv.2003.60.11',
'10.24199/j.mmv.2003.60.12',
'10.24199/j.mmv.2004.61.1',
'10.24199/j.mmv.2004.61.10',
'10.24199/j.mmv.2004.61.11',
'10.24199/j.mmv.2004.61.12',
'10.24199/j.mmv.2004.61.13',
'10.24199/j.mmv.2004.61.14',
'10.24199/j.mmv.2004.61.3',
'10.24199/j.mmv.2004.61.4',
'10.24199/j.mmv.2004.61.5',
'10.24199/j.mmv.2004.61.2',
'10.24199/j.mmv.2004.61.6',
'10.24199/j.mmv.2004.61.7',
'10.24199/j.mmv.2004.61.8',
'10.24199/j.mmv.2004.61.9',
'10.24199/j.mmv.2005.62.2',
'10.24199/j.mmv.2005.62.1',
'10.24199/j.mmv.2005.62.4',
'10.24199/j.mmv.2005.62.5',
'10.24199/j.mmv.2005.62.6',
'10.24199/j.mmv.2005.62.3',
'10.24199/j.mmv.2005.62.7',
'10.24199/j.mmv.2005.62.8',
'10.24199/j.mmv.2006.63.10',
'10.24199/j.mmv.2006.63.1',
'10.24199/j.mmv.2006.63.11',
'10.24199/j.mmv.2006.63.12',
'10.24199/j.mmv.2006.63.14',
'10.24199/j.mmv.2006.63.15',
'10.24199/j.mmv.2006.63.16',
'10.24199/j.mmv.2006.63.13',
'10.24199/j.mmv.2006.63.17',
'10.24199/j.mmv.2006.63.18',
'10.24199/j.mmv.2006.63.2',
'10.24199/j.mmv.2006.63.3',
'10.24199/j.mmv.2006.63.4',
'10.24199/j.mmv.2006.63.5',
'10.24199/j.mmv.2006.63.7',
'10.24199/j.mmv.2006.63.8',
'10.24199/j.mmv.2006.63.6',
'10.24199/j.mmv.2006.63.9',
'10.24199/j.mmv.2007.64.10',
'10.24199/j.mmv.2007.64.1',
'10.24199/j.mmv.2007.64.11',
'10.24199/j.mmv.2007.64.3',
'10.24199/j.mmv.2007.64.2',
'10.24199/j.mmv.2007.64.6',
'10.24199/j.mmv.2007.64.8',
'10.24199/j.mmv.2007.64.5',
'10.24199/j.mmv.2007.64.7',
'10.24199/j.mmv.2007.64.4',
'10.24199/j.mmv.2007.64.9',
'10.24199/j.mmv.2008.65.10',
'10.24199/j.mmv.2008.65.11',
'10.24199/j.mmv.2008.65.12',
'10.24199/j.mmv.2008.65.1',
'10.24199/j.mmv.2008.65.2',
'10.24199/j.mmv.2008.65.4',
'10.24199/j.mmv.2008.65.5',
'10.24199/j.mmv.2008.65.6',
'10.24199/j.mmv.2008.65.7',
'10.24199/j.mmv.2008.65.8',
'10.24199/j.mmv.2008.65.9',
'10.24199/j.mmv.2008.65.3',
'10.24199/j.mmv.2009.66.1',
'10.24199/j.mmv.2009.66.14',
'10.24199/j.mmv.2009.66.15',
'10.24199/j.mmv.2009.66.18',
'10.24199/j.mmv.2009.66.19',
'10.24199/j.mmv.2009.66.21',
'10.24199/j.mmv.2009.66.20',
'10.24199/j.mmv.2009.66.3',
'10.24199/j.mmv.2009.66.4',
'10.24199/j.mmv.2009.66.2',
'10.24199/j.mmv.2009.66.5',
'10.24199/j.mmv.2009.66.6',
'10.24199/j.mmv.2009.66.7',
'10.24199/j.mmv.2009.66.9',
'10.24199/j.mmv.2009.66.10',
'10.24199/j.mmv.2009.66.11',
'10.24199/j.mmv.2009.66.12',
'10.24199/j.mmv.2009.66.17',
'10.24199/j.mmv.2009.66.13',
'10.24199/j.mmv.2009.66.16',
'10.24199/j.mmv.2010.67.01',
'10.24199/j.mmv.2010.67.03',
'10.24199/j.mmv.2010.67.02',
'10.24199/j.mmv.2010.67.04',
'10.24199/j.mmv.2010.67.05',
'10.24199/j.mmv.2010.67.06',
'10.24199/j.mmv.2011.68.01',
'10.24199/j.mmv.2011.68.02',
'10.24199/j.mmv.2011.68.03',
'10.24199/j.mmv.2011.68.04',
'10.24199/j.mmv.2011.68.05',
'10.24199/j.mmv.2012.69.01',
'10.24199/j.mmv.2012.69.02',
'10.24199/j.mmv.2012.69.03',
'10.24199/j.mmv.2012.69.06',
'10.24199/j.mmv.2012.69.05',
'10.24199/j.mmv.2012.69.04',
'10.24199/j.mmv.2012.69.07',
'10.24199/j.mmv.2012.69.08',
'10.24199/j.mmv.2012.69.09',
'10.24199/j.mmv.2013.70.04',
'10.24199/j.mmv.2013.70.03',
'10.24199/j.mmv.2013.70.05',
'10.24199/j.mmv.2013.70.02',
'10.24199/j.mmv.2013.70.01',
'10.24199/j.mmv.2014.71.04',
'10.24199/j.mmv.2014.71.05',
'10.24199/j.mmv.2014.71.06',
'10.24199/j.mmv.2014.71.07',
'10.24199/j.mmv.2014.71.12',
'10.24199/j.mmv.2014.71.13',
'10.24199/j.mmv.2014.71.14',
'10.24199/j.mmv.2014.71.17',
'10.24199/j.mmv.2014.71.19',
'10.24199/j.mmv.2014.71.18',
'10.24199/j.mmv.2014.72.04',
'10.24199/j.mmv.2014.72.07',
'10.24199/j.mmv.2014.71.01',
'10.24199/j.mmv.2014.71.02',
'10.24199/j.mmv.2014.71.03',
'10.24199/j.mmv.2014.71.08',
'10.24199/j.mmv.2014.71.10',
'10.24199/j.mmv.2014.71.11',
'10.24199/j.mmv.2014.71.09',
'10.24199/j.mmv.2014.71.15',
'10.24199/j.mmv.2014.71.16',
'10.24199/j.mmv.2014.71.22',
'10.24199/j.mmv.2014.71.23',
'10.24199/j.mmv.2014.71.25',
'10.24199/j.mmv.2014.71.26',
'10.24199/j.mmv.2015.73.01',
'10.24199/j.mmv.2015.73.03',
'10.24199/j.mmv.2015.73.06',
'10.24199/j.mmv.2015.73.04',
'10.24199/j.mmv.2015.73.02',
'10.24199/j.mmv.2015.73.08',
'10.24199/j.mmv.2015.73.07',
'10.24199/j.mmv.2015.73.09',
'10.24199/j.mmv.2015.73.05',
'10.24199/j.mmv.2016.74.02',
'10.24199/j.mmv.2016.74.03',
'10.24199/j.mmv.2016.74.04',
'10.24199/j.mmv.2016.74.06',
'10.24199/j.mmv.2016.74.05',
'10.24199/j.mmv.2016.74.07',
'10.24199/j.mmv.2016.74.08',
'10.24199/j.mmv.2016.74.09',
'10.24199/j.mmv.2016.74.11',
'10.24199/j.mmv.2016.74.15',
'10.24199/j.mmv.2016.74.23',
'10.24199/j.mmv.2016.74.25',
'10.24199/j.mmv.2016.74.13',
'10.24199/j.mmv.2016.74.26',
'10.24199/j.mmv.2016.74.27',
'10.24199/j.mmv.2016.74.24',
'10.24199/j.mmv.2016.75.01',
'10.24199/j.mmv.2016.75.02',
'10.24199/j.mmv.2016.74.28',
'10.24199/j.mmv.2016.75.03',
'10.24199/j.mmv.2016.75.04',
'10.24199/j.mmv.1999.57.09',
'10.24199/j.mmv.1999.57.10',
'10.24199/j.mmv.1999.57.11',
'10.24199/j.mmv.1999.57.13',
'10.24199/j.mmv.1999.57.12',
'10.24199/j.mmv.1999.57.14',
'10.24199/j.mmv.1999.57.15',
'10.24199/j.mmv.1999.57.16',
'10.24199/j.mmv.1999.57.17',
'10.24199/j.mmv.1999.57.18',
'10.24199/j.mmv.2003.60.21',
'10.24199/j.mmv.2003.60.24',
'10.24199/j.mmv.2003.60.25',
'10.24199/j.mmv.2003.60.26',
'10.24199/j.mmv.2003.60.27',
'10.24199/j.mmv.2003.60.29',
'10.24199/j.mmv.2003.60.28',

'10.24199/j.mmv.1946.14.11',
'10.24199/j.mmv.1946.14.09',
'10.24199/j.mmv.1946.14.04',
'10.24199/j.mmv.1946.14.08',
'10.24199/j.mmv.1946.14.05',
'10.24199/j.mmv.1946.14.06',
'10.24199/j.mmv.1946.14.07',
'10.24199/j.mmv.1946.14.10',
'10.24199/j.mmv.1947.15.09',
'10.24199/j.mmv.1947.15.02',
'10.24199/j.mmv.1947.15.05',
'10.24199/j.mmv.1947.15.07',
'10.24199/j.mmv.1947.15.16',
'10.24199/j.mmv.1947.15.08',
'10.24199/j.mmv.1947.15.18',
'10.24199/j.mmv.1947.15.10',
'10.24199/j.mmv.1947.15.21',
'10.24199/j.mmv.1947.15.12',
'10.24199/j.mmv.1947.15.13',
'10.24199/j.mmv.1947.15.17',
'10.24199/j.mmv.1947.15.01',
'10.24199/j.mmv.1947.15.11',
'10.24199/j.mmv.1947.15.20',
'10.24199/j.mmv.1947.15.19',
'10.24199/j.mmv.1947.15.03',
'10.24199/j.mmv.1947.15.06',
'10.24199/j.mmv.1947.15.15',
'10.24199/j.mmv.1947.15.22',
'10.24199/j.mmv.1949.16.05',
'10.24199/j.mmv.1949.16.04',
'10.24199/j.mmv.1949.16.02',
'10.24199/j.mmv.1949.16.01',
'10.24199/j.mmv.1949.16.06',
'10.24199/j.mmv.1949.16.07',
'10.24199/j.mmv.1949.16.03',
'10.24199/j.mmv.1951.17.12',
'10.24199/j.mmv.1951.17.13',
'10.24199/j.mmv.1951.17.09',
'10.24199/j.mmv.1951.17.08',
'10.24199/j.mmv.1951.17.05',
'10.24199/j.mmv.1951.17.01',
'10.24199/j.mmv.1951.17.06',
'10.24199/j.mmv.1951.17.07',
'10.24199/j.mmv.1951.17.03',
'10.24199/j.mmv.1951.17.04',
'10.24199/j.mmv.1953.18.05',
'10.24199/j.mmv.1953.18.03',
'10.24199/j.mmv.1953.18.01',
'10.24199/j.mmv.1953.18.04',
'10.24199/j.mmv.1953.18.09',
'10.24199/j.mmv.1953.18.07',
'10.24199/j.mmv.1953.18.02',
'10.24199/j.mmv.1953.18.08',
'10.24199/j.mmv.1953.18.10',
'10.24199/j.mmv.1953.18.06',
'10.24199/j.mmv.1955.19.02',
'10.24199/j.mmv.1955.19.01',
'10.24199/j.mmv.1955.19.05',
'10.24199/j.mmv.1955.19.03',
'10.24199/j.mmv.1955.19.04',
'10.24199/j.mmv.1956.20.05',
'10.24199/j.mmv.1956.20.03',
'10.24199/j.mmv.1956.20.02',
'10.24199/j.mmv.1956.22.03',
'10.24199/j.mmv.1956.20.04',
'10.24199/j.mmv.1956.22.02',
'10.24199/j.mmv.1956.20.01',
'10.24199/j.mmv.1956.22.04',
'10.24199/j.mmv.1956.22.01',
'10.24199/j.mmv.1957.21.10',
'10.24199/j.mmv.1957.21.09',
'10.24199/j.mmv.1957.21.02',
'10.24199/j.mmv.1957.22.07',
'10.24199/j.mmv.1957.21.06',
'10.24199/j.mmv.1957.21.03',
'10.24199/j.mmv.1957.21.08',
'10.24199/j.mmv.1957.22.09',
'10.24199/j.mmv.1957.22.06',
'10.24199/j.mmv.1957.21.05',
'10.24199/j.mmv.1957.22.08',
'10.24199/j.mmv.1957.21.07',
'10.24199/j.mmv.1957.21.04',
'10.24199/j.mmv.1957.22.05',
'10.24199/j.mmv.1957.21.01',
'10.24199/j.mmv.1959.23.01',
'10.24199/j.mmv.1959.24.09',
'10.24199/j.mmv.1959.24.04',
'10.24199/j.mmv.1959.24.08',
'10.24199/j.mmv.1959.24.06',
'10.24199/j.mmv.1959.24.07',
'10.24199/j.mmv.1959.24.03',
'10.24199/j.mmv.1959.24.05',
'10.24199/j.mmv.1959.24.10',
'10.24199/j.mmv.1959.24.02',
'10.24199/j.mmv.1961.22.10',
'10.24199/j.mmv.1962.25.07',
'10.24199/j.mmv.1962.25.02',
'10.24199/j.mmv.1962.25.03',
'10.24199/j.mmv.1962.25.12',
'10.24199/j.mmv.1962.25.04',
'10.24199/j.mmv.1962.25.01',
'10.24199/j.mmv.1962.25.14',
'10.24199/j.mmv.1962.25.15',
'10.24199/j.mmv.1962.25.09',
'10.24199/j.mmv.1962.25.10',
'10.24199/j.mmv.1964.26.02',
'10.24199/j.mmv.1964.26.08',
'10.24199/j.mmv.1964.26.09',
'10.24199/j.mmv.1964.26.06',
'10.24199/j.mmv.1964.26.11',
'10.24199/j.mmv.1964.26.07',
'10.24199/j.mmv.1964.26.05',
'10.24199/j.mmv.1964.26.13',
'10.24199/j.mmv.1964.26.12',
'10.24199/j.mmv.1964.26.03',
'10.24199/j.mmv.1966.27.16',
'10.24199/j.mmv.1966.27.04',
'10.24199/j.mmv.1966.27.06',
'10.24199/j.mmv.1966.27.14',
'10.24199/j.mmv.1966.27.08',
'10.24199/j.mmv.1966.27.02',
'10.24199/j.mmv.1966.27.17',
'10.24199/j.mmv.1966.27.07',
'10.24199/j.mmv.1966.27.10',
'10.24199/j.mmv.1966.27.01',
'10.24199/j.mmv.1968.28.03',
'10.24199/j.mmv.1968.28.06',
'10.24199/j.mmv.1968.28.05',
'10.24199/j.mmv.1968.28.07',
'10.24199/j.mmv.1968.28.04',
'10.24199/j.mmv.1968.28.02',
'10.24199/j.mmv.1968.28.01',
'10.24199/j.mmv.1968.28.08',
'10.24199/j.mmv.1969.29.03',
'10.24199/j.mmv.1969.29.06',
'10.24199/j.mmv.1969.29.07',
'10.24199/j.mmv.1969.29.02',
'10.24199/j.mmv.1969.29.09',
'10.24199/j.mmv.1969.29.04',
'10.24199/j.mmv.1969.29.01',
'10.24199/j.mmv.1969.29.08',
'10.24199/j.mmv.1969.29.05',
'10.24199/j.mmv.1970.31.02',
'10.24199/j.mmv.1970.31.07',
'10.24199/j.mmv.1970.31.03',
'10.24199/j.mmv.1970.30.05',
'10.24199/j.mmv.1970.31.01',
'10.24199/j.mmv.1970.31.04',
'10.24199/j.mmv.1970.31.11',
'10.24199/j.mmv.1970.30.06',
'10.24199/j.mmv.1970.31.06',
'10.24199/j.mmv.1970.31.13',
'10.24199/j.mmv.1970.31.08',
'10.24199/j.mmv.1970.31.12',
'10.24199/j.mmv.1970.31.05',
'10.24199/j.mmv.1970.30.01',
'10.24199/j.mmv.1970.30.02',
'10.24199/j.mmv.1970.31.09',
'10.24199/j.mmv.1970.30.04',
'10.24199/j.mmv.1970.31.10',
'10.24199/j.mmv.1970.30.03',
'10.24199/j.mmv.1970.31.14',
'10.24199/j.mmv.1971.32.01',
'10.24199/j.mmv.1971.32.08',
'10.24199/j.mmv.1971.32.04',
'10.24199/j.mmv.1971.32.02',
'10.24199/j.mmv.1971.32.10',
'10.24199/j.mmv.1971.32.05',
'10.24199/j.mmv.1971.32.07',
'10.24199/j.mmv.1971.32.09',
'10.24199/j.mmv.1971.32.06',
'10.24199/j.mmv.1971.32.03',
'10.24199/j.mmv.1972.33.03',
'10.24199/j.mmv.1972.33.06',
'10.24199/j.mmv.1972.33.17',
'10.24199/j.mmv.1972.33.09',
'10.24199/j.mmv.1972.33.02',
'10.24199/j.mmv.1972.33.07',
'10.24199/j.mmv.1972.33.04',
'10.24199/j.mmv.1972.33.10',
'10.24199/j.mmv.1972.33.08',
'10.24199/j.mmv.1972.33.01',
'10.24199/j.mmv.1972.33.05',
'10.24199/j.mmv.1972.33.11',
'10.24199/j.mmv.1972.33.13',
'10.24199/j.mmv.1972.33.15',
'10.24199/j.mmv.1972.33.12',
'10.24199/j.mmv.1972.33.14',
'10.24199/j.mmv.1972.33.18',
'10.24199/j.mmv.1972.33.16',
'10.24199/j.mmv.1973.34.03',
'10.24199/j.mmv.1973.34.01',
'10.24199/j.mmv.1973.34.14',
'10.24199/j.mmv.1973.34.07',
'10.24199/j.mmv.1973.34.10',
'10.24199/j.mmv.1973.34.04',
'10.24199/j.mmv.1973.34.02',
'10.24199/j.mmv.1973.34.05',
'10.24199/j.mmv.1973.34.06',
'10.24199/j.mmv.1973.34.08',
'10.24199/j.mmv.1973.34.15',
'10.24199/j.mmv.1973.34.17',
'10.24199/j.mmv.1973.34.21',
'10.24199/j.mmv.1973.34.11',
'10.24199/j.mmv.1973.34.12',
'10.24199/j.mmv.1973.34.13',
'10.24199/j.mmv.1973.34.16',
'10.24199/j.mmv.1973.34.18',
'10.24199/j.mmv.1973.34.22',
'10.24199/j.mmv.1973.34.19',
'10.24199/j.mmv.1974.35.06',
'10.24199/j.mmv.1974.35.03',
'10.24199/j.mmv.1974.35.05',
'10.24199/j.mmv.1974.35.02',
'10.24199/j.mmv.1974.35.04',
'10.24199/j.mmv.1974.35.07',
'10.24199/j.mmv.1974.35.01',
'10.24199/j.mmv.1975.36.05',
'10.24199/j.mmv.1975.36.03',
'10.24199/j.mmv.1975.36.04',
'10.24199/j.mmv.1975.36.02',
'10.24199/j.mmv.1975.36.01',
'10.24199/j.mmv.1976.37.01',
'10.24199/j.mmv.1976.37.03',
'10.24199/j.mmv.1976.37.02',
'10.24199/j.mmv.1976.37.04',
'10.24199/j.mmv.1976.37.05',
'10.24199/j.mmv.1976.37.07',
'10.24199/j.mmv.1976.37.06',
'10.24199/j.mmv.1977.38.02',
'10.24199/j.mmv.1977.38.01',
'10.24199/j.mmv.1977.38.04',
'10.24199/j.mmv.1977.38.03',
'10.24199/j.mmv.1977.38.06',
'10.24199/j.mmv.1977.38.05',
'10.24199/j.mmv.1978.39.10',
'10.24199/j.mmv.1978.39.12',
'10.24199/j.mmv.1978.39.02',
'10.24199/j.mmv.1978.39.01',
'10.24199/j.mmv.1978.39.09',
'10.24199/j.mmv.1978.39.08',
'10.24199/j.mmv.1978.39.04',
'10.24199/j.mmv.1978.39.06',
'10.24199/j.mmv.1978.39.07',
'10.24199/j.mmv.1978.39.05',
'10.24199/j.mmv.1979.40.01',
'10.24199/j.mmv.1979.40.02',
'10.24199/j.mmv.1979.40.04',
'10.24199/j.mmv.1979.40.03',
'10.24199/j.mmv.1979.40.05',
'10.24199/j.mmv.1980.41.03',
'10.24199/j.mmv.1980.41.05',
'10.24199/j.mmv.1980.41.04',
'10.24199/j.mmv.1980.41.01',
'10.24199/j.mmv.1980.41.02',
'10.24199/j.mmv.1981.42.05',
'10.24199/j.mmv.1981.42.03',
'10.24199/j.mmv.1981.42.02',
'10.24199/j.mmv.1981.42.04',
'10.24199/j.mmv.1981.42.01',
'10.24199/j.mmv.1981.42.06',
'10.24199/j.mmv.1982.43.03',
'10.24199/j.mmv.1982.43.05',
'10.24199/j.mmv.1982.43.06',
'10.24199/j.mmv.1982.43.01',
'10.24199/j.mmv.1982.43.02',
'10.24199/j.mmv.1982.43.07',
'10.24199/j.mmv.1982.43.08',
'10.24199/j.mmv.1982.43.04',
'10.24199/j.mmv.1983.44.10',
'10.24199/j.mmv.1983.44.02',
'10.24199/j.mmv.1983.44.19',
'10.24199/j.mmv.1983.44.24',
'10.24199/j.mmv.1983.44.22',
'10.24199/j.mmv.1983.44.01',
'10.24199/j.mmv.1983.44.06',
'10.24199/j.mmv.1983.44.08',
'10.24199/j.mmv.1983.44.16',
'10.24199/j.mmv.1983.44.04',
'10.24199/j.mmv.1983.44.03',
'10.24199/j.mmv.1983.44.05',
'10.24199/j.mmv.1983.44.07',
'10.24199/j.mmv.1983.44.12',
'10.24199/j.mmv.1983.44.09',
'10.24199/j.mmv.1983.44.17',
'10.24199/j.mmv.1983.44.18',
'10.24199/j.mmv.1983.44.25',
'10.24199/j.mmv.1983.44.14',
'10.24199/j.mmv.1983.44.20',
'10.24199/j.mmv.1962.25.06',
'10.24199/j.mmv.1964.26.10',
'10.24199/j.mmv.1964.26.01',
'10.24199/j.mmv.1978.39.03',

'10.24199/j.mmv.1906.1.01',
'10.24199/j.mmv.1908.2.01',
'10.24199/j.mmv.1910.3.02',
'10.24199/j.mmv.1910.3.01',
'10.24199/j.mmv.1912.4.03',
'10.24199/j.mmv.1912.4.08',
'10.24199/j.mmv.1912.4.04',
'10.24199/j.mmv.1912.4.01',
'10.24199/j.mmv.1912.4.07',
'10.24199/j.mmv.1912.4.02',
'10.24199/j.mmv.1912.4.05',
'10.24199/j.mmv.1912.4.06',
'10.24199/j.mmv.1914.5.02',
'10.24199/j.mmv.1914.5.03',
'10.24199/j.mmv.1914.5.01',
'10.24199/j.mmv.1915.6.01',
'10.24199/j.mmv.1927.7.02',
'10.24199/j.mmv.1927.7.03',
'10.24199/j.mmv.1927.7.01',
'10.24199/j.mmv.1934.8.07',
'10.24199/j.mmv.1934.8.10',
'10.24199/j.mmv.1934.8.05',
'10.24199/j.mmv.1934.8.12',
'10.24199/j.mmv.1934.8.04',
'10.24199/j.mmv.1934.8.01',
'10.24199/j.mmv.1934.8.09',
'10.24199/j.mmv.1934.8.03',
'10.24199/j.mmv.1934.8.02',
'10.24199/j.mmv.1934.8.13',
'10.24199/j.mmv.1934.8.08',
'10.24199/j.mmv.1934.8.06',
'10.24199/j.mmv.1934.8.14',
'10.24199/j.mmv.1934.8.11',
'10.24199/j.mmv.1936.9.02',
'10.24199/j.mmv.1936.9.03',
'10.24199/j.mmv.1936.9.04',
'10.24199/j.mmv.1936.9.06',
'10.24199/j.mmv.1936.9.01',
'10.24199/j.mmv.1936.9.05',
'10.24199/j.mmv.1936.10.02',
'10.24199/j.mmv.1936.10.06',
'10.24199/j.mmv.1936.10.05',
'10.24199/j.mmv.1936.10.03',
'10.24199/j.mmv.1936.10.01',
'10.24199/j.mmv.1936.10.04',
'10.24199/j.mmv.1939.11.02',
'10.24199/j.mmv.1939.11.03',
'10.24199/j.mmv.1939.11.01',
'10.24199/j.mmv.1939.11.04',
'10.24199/j.mmv.1939.11.05',
'10.24199/j.mmv.1941.12.07',
'10.24199/j.mmv.1941.12.06',
'10.24199/j.mmv.1941.12.01',
'10.24199/j.mmv.1941.12.08',
'10.24199/j.mmv.1941.12.04',
'10.24199/j.mmv.1941.12.05',
'10.24199/j.mmv.1941.12.02',
'10.24199/j.mmv.1941.12.03',
'10.24199/j.mmv.1943.13.02',
'10.24199/j.mmv.1943.13.01',
'10.24199/j.mmv.1943.13.03',
'10.24199/j.mmv.1943.13.06',
'10.24199/j.mmv.1943.13.05',
'10.24199/j.mmv.1943.13.04',
'10.24199/j.mmv.1943.13.07',
'10.24199/j.mmv.1944.14.03',
'10.24199/j.mmv.1944.14.01',
'10.24199/j.mmv.1944.14.02',

);

$dois=array(

"10.3853/j.2201-4349.65.2013.1600",
"10.3853/j.0067-1975.36.1985.348",
"10.3853/j.0067-1975.14.1923.831",
"10.3853/j.0067-1975.5.1904.1053",
"10.3853/j.0067-1975.4.1901.1079",
"10.3853/j.0067-1975.6.1907.1020",
"10.3853/j.2201-4349.66.2014.1630",
"10.3853/j.2201-4349.67.2015.1640",
"10.3853/j.0067-1975.4.1901.1082",
"10.3853/j.0067-1975.52.2000.1317",
"10.3853/j.0067-1975.39.1987.171",
"10.3853/j.0067-1975.32.1979.465",
"10.3853/j.0812-7387.23.1997.429",
"10.3853/j.0067-1975.50.1998.1277",
"10.3853/j.2201-4349.67.2015.1641",
"10.3853/j.2201-4349.68.2016.1652",
"10.3853/j.0067-1975.18.1931.720",
"10.3853/j.0067-1975.14.1923.833",
"10.3853/j.0067-1975.37.1985.337",
"10.3853/j.0067-1975.15.1926.808",
"10.3853/j.0067-1975.15.1926.812",
"10.3853/j.0067-1975.18.1931.727",
"10.3853/j.0067-1975.4.1901.1081",
"10.3853/j.0067-1975.4.1901.1080",
"10.3853/j.0067-1975.4.1902.1094",
"10.3853/j.2201-4349.67.2015.1646",
"10.3853/j.2201-4349.67.2015.1634",
"10.3853/j.2201-4349.66.2014.1637",
"10.3853/j.2201-4349.68.2016.1598",
"10.3853/j.2201-4349.65.2013.1597",
"10.3853/j.0067-1975.5.1904.1072",
"10.3853/j.0067-1975.56.2004.1435",
"10.3853/j.2201-4349.65.2013.1592",
"10.3853/j.0067-1975.50.1998.1279",
"10.3853/j.2201-4349.67.2015.1639",
"10.3853/j.2201-4349.66.2014.1601",
"10.3853/j.2201-4349.66.2014.1602",
"10.3853/j.0067-1975.15.1926.807",
"10.3853/j.0067-1975.44.1992.34",
"10.3853/j.0067-1975.6.1905.981",
"10.3853/j.0067-1975.4.1902.1099",
"10.3853/j.0067-1975.20.1937.257",
"10.3853/j.2201-4349.67.2015.1651",
"10.3853/j.0067-1975.15.1926.809",
"10.3853/j.0067-1975.5.1904.1066",
"10.3853/j.2201-4349.68.2016.1657",
"10.3853/j.0812-7387.4.1985.100",
"10.3853/j.0067-1975.32.1979.203",
"10.3853/j.0067-1975.46.1994.17",
"10.3853/j.2201-4349.67.2015.1644",
"10.3853/j.2201-4349.66.2014.1594",
"10.3853/j.2201-4349.66.2014.1595",
"10.3853/j.2201-4349.66.2014.1596",
"10.3853/j.0067-1975.62.2010.1554",
"10.3853/j.0067-1975.36.1985.346",
"10.3853/j.2201-4349.66.2014.1631",
"10.3853/j.0067-1975.50.1998.1280",
"10.3853/j.0067-1975.63.2011.1552",
"10.3853/j.0067-1975.16.1928.792",
"10.3853/j.0812-7387.18.1993.53",
"10.3853/j.2201-4349.67.2015.1650",
"10.3853/j.0067-1975.32.1979.468",
"10.3853/j.0067-1975.20.1937.259",
"10.3853/j.0067-1975.36.1985.345",
"10.3853/j.0812-7387.8.1988.96",
"10.3853/j.0067-1975.2.1893.1192",
"10.3853/j.0067-1975.32.1979.470",
"10.3853/j.0067-1975.42.1990.107",
"10.3853/j.0067-1975.43.1991.47",
"10.3853/j.0067-1975.37.1985.339",
"10.3853/j.0067-1975.4.1902.1108",
"10.3853/j.0067-1975.42.1990.106",
"10.3853/j.0067-1975.36.1985.341",
"10.3853/j.0067-1975.6.1907.1019",
"10.3853/j.0067-1975.5.1904.1061",
"10.3853/j.2201-4349.66.2014.1633",
"10.3853/j.0067-1975.56.2004.1430",
"10.3853/j.0067-1975.16.1928.791",
"10.3853/j.0067-1975.2.1893.1200",
"10.3853/j.0067-1975.20.1937.260",
"10.3853/j.0067-1975.32.1979.464",
"10.3853/j.0067-1975.32.1979.469",
"10.3853/j.2201-4349.66.2014.1599",
"10.3853/j.0812-7387.24.1998.1267",
"10.3853/j.0067-1975.42.1990.108",
"10.3853/j.0067-1975.32.1979.459",
"10.3853/j.0067-1975.18.1932.739",
"10.3853/j.0067-1975.1.1890.1215",
"10.3853/j.0067-1975.36.1985.343",
"10.3853/j.0067-1975.56.2004.1434",
"10.3853/j.2201-4349.68.2016.1656",
"10.3853/j.0067-1975.50.1998.1281",
"10.3853/j.0812-7387.26.2001.1333",
"10.3853/j.0067-1975.32.1979.466",
"10.3853/j.0067-1975.44.1992.36",
"10.3853/j.0067-1975.40.1988.150",
"10.3853/j.0067-1975.56.2004.1431",
"10.3853/j.0067-1975.1.1890.1234",
"10.3853/j.0067-1975.5.1903.1025",
"10.3853/j.0812-7387.2.1983.102",
"10.3853/j.0067-1975.4.1902.1111",
"10.3853/j.0812-7387.28.2003.1377",
"10.3853/j.0067-1975.36.1985.347",
"10.3853/j.0067-1975.14.1923.835",

);

$dois=array(
'10.3853/j.0067-1975.32.1979.458'
);

$count = 1;

foreach ($dois as $doi)
{
	$url = 'https://doi.org/' . $doi;
	
	echo "-- $doi\n";

	$html = get($url);
	
	//echo $html;
	
	if ($html != '')
	{
		$dom = str_get_html($html);

		$abstract_tag = 'h2';
		$abstract_tag = 'h3';
		
		// Abstract
		foreach ($dom->find($abstract_tag) as $tag)
		{
			// echo $tag->plaintext . "\n";
			
			if ($tag->plaintext == 'Abstract')
			{
				$parent = $tag->parent();
				
				$children = $parent->children();
				
				$index = -1;
				$i = 0;
				$n = count($children);
				while ($i < $n)
				{
					if ($i > 0)
					{
						if (
							($children[$i]->tag == 'p')
							&& ($children[$i-1]->plaintext == 'Abstract')
							)
						{
							$index = $i;
						}
					}
					$i++;
				}
				
				if ($index != -1)
				{
					//echo $children[$index]->plaintext . "\n";
					
					$text = $children[$index]->plaintext;
					
					$text = preg_replace('/\x0D\x0A\s+/u', "\n", $text);
					
					echo 'UPDATE publications SET `abstract`="' . addcslashes($text, '""') . '" WHERE doi="' . $doi . '";' . "\n";
				}
			
				/*
				echo "xxx\n";
				foreach ($h3->next_sibling() as $p)
				{
					echo $p->tag . "|\n";
					echo $p->plaintext . "|\n";
				}
				echo "-----\n";
				*/
			
			}
		}
		
		// Give server a break every 10 items
		if (($count++ % 10) == 0)
		{
			$rand = rand(1000000, 3000000);
			echo "\n-- ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
			usleep($rand);
		}
	}
	
}




?>