<!--

// This script supports an unlimited number of linked combo boxed
// Their id must be "combo_0", "combo_1", "combo_2" etc.
// Here you have to put the data that will fill the combo boxes
// ie. data_2_1 will be the first option in the second combo box
// when the first combo box has the second option selected


//  combo box

	// Abruzzo
	data_1 = new Option("Abruzzo", "Abruzzo");
	//Provincie
	data_1_1 = new Option("L'Aquila", "L'Aquila");
	data_1_2 = new Option("Chieti", "Chieti");
	data_1_3 = new Option("Pescara", "Pescara");
	data_1_4 = new Option("Teramo", "Teramo");
	
	// Basilicata
	data_2 = new Option("Basilicata", "Basilicata");
	//Provincie
	data_2_1 = new Option("Matera", "Matera");
	data_2_2 = new Option("Potenza", "Potenza");
	
	// Calabria
	data_3 = new Option("Calabria", "Calabria");
	//Provincie
	data_3_1 = new Option("Catanzaro", "Catanzaro");
	data_3_2 = new Option("Cosenza", "Cosenza");
	data_3_3 = new Option("Crotone", "Crotone");
	data_3_4 = new Option("Reggio Calabria", "Reggio Calabria");
	data_3_5 = new Option("Vibo Valentia", "Vibo Valentia");

	// Campania
	data_4 = new Option("Campania", "Campania");
	//Provincie
	data_4_1 = new Option("Avellino", "Avellino");
	data_4_2 = new Option("Benevento", "Benevento");
	data_4_3 = new Option("Caserta", "Caserta");
	data_4_4 = new Option("Napoli", "Napoli");
	data_4_5 = new Option("Salerno", "Salerno");
	
	// Emilia-Romagna
	data_5 = new Option("Emilia-Romagna", "Emilia-Romagna");
	//Provincie
	data_5_1 = new Option("Bologna", "Bologna");
	data_5_2 = new Option("Ferrara", "Ferrara");
	data_5_3 = new Option("Forli'-Cesena", "Forli'-Cesena");
	data_5_4 = new Option("Modena", "Modena");
	data_5_5 = new Option("Parma", "Parma");
	data_5_6 = new Option("Piacenza", "Piacenza");
	data_5_6 = new Option("Ravenna", "Ravenna");
	data_5_7 = new Option("Reggio Emilia", "Reggio Emilia");
	data_5_8 = new Option("Rimini", "Rimini");
	
	// Friuli-Venezia Giulia
	data_6 = new Option("Friuli-Venezia Giulia", "Friuli-Venezia Giulia");
	//Provincie
	data_6_1 = new Option("Gorizia", "Gorizia");
	data_6_2 = new Option("Pordenone", "Pordenone");
	data_6_3 = new Option("Trieste", "Trieste");
	data_6_4 = new Option("Udine", "Udine");
	
	// Lazio
	data_7 = new Option("Lazio", "Lazio");
	//Provincie
	data_7_1 = new Option("Frosinone", "Frosinone");
	data_7_2 = new Option("Latina", "Latina");
	data_7_3 = new Option("Rieti", "Rieti");
	data_7_4 = new Option("Roma", "Roma");
	data_7_5 = new Option("Viterbo", "Viterbo");
	
	// Liguria
	data_8 = new Option("Liguria", "Liguria");
	//Provincie
	data_8_1 = new Option("Genova", "Genova");
	data_8_2 = new Option("Imperia", "Imperia");
	data_8_3 = new Option("La Spezia", "La Spezia");
	data_8_4 = new Option("Savona", "Savona");
	
	// Lombardia
	data_9 = new Option("Lombardia", "Lombardia");
	//Provincie
	data_9_1 = new Option("Bergamo", "Bergamo");
	data_9_2 = new Option("Brescia", "Brescia");
	data_9_3 = new Option("Como", "Como");
	data_9_4 = new Option("Cremona", "Cremona");
	data_9_5 = new Option("Lecco", "Lecco");
	data_9_6 = new Option("Lodi", "Lodi");
	data_9_7 = new Option("Mantova", "Mantova");
	data_9_8 = new Option("Milano", "Milano");
	data_9_9 = new Option("Monza e Brianza", "Monza e Brianza");
	data_9_10 = new Option("Pavia", "Pavia");
	data_9_11 = new Option("Sondrio", "Sondrio");
	data_9_12 = new Option("Cremona", "Cremona");
	data_9_13 = new Option("Varese", "Varese");

	// Marche
	data_10 = new Option("Marche", "Marche");
	//Provincie
	data_10_1 = new Option("Ancona", "Ancona");
	data_10_2 = new Option("Ascoli Piceno", "Ascoli Piceno");
	data_10_3 = new Option("Como", "Como");
	data_10_4 = new Option("Fermo", "Fermo");
	data_10_5 = new Option("Macerata e Urbino", "Macerata e Urbino");

	// Molise
	data_11 = new Option("Molise", "Molise");
	//Provincie
	data_11_1 = new Option("Campobasso", "Campobasso");
	data_11_2 = new Option("Isernia", "Isernia");
	
	// Piemonte
	data_12 = new Option("Piemonte", "Piemonte");
	//Provincie
	data_12_1 = new Option("Alessandria", "Campobasso");
	data_12_2 = new Option("Asti", "Isernia");
	data_12_3 = new Option("Biella", "Isernia");
	data_12_4 = new Option("Cuneo", "Isernia");
	data_12_5 = new Option("Novara", "Isernia");
	data_12_6 = new Option("Torino", "Isernia");
	data_12_7 = new Option("Verbano-Cusio-Ossola", "Verbano-Cusio-Ossola");
	data_12_8 = new Option("Vercelli", "Vercelli");
	
	// Puglia
	data_13 = new Option("Puglia", "Puglia");
	//Provincie
	data_13_1 = new Option("Bari", "Bari");
	data_13_2 = new Option("Barletta-Andria-Trani", "Barletta-Andria-Trani");
	data_13_3 = new Option("Brindisi", "Brindisi");
	data_13_4 = new Option("Foggia", "Foggia");
	data_13_5 = new Option("Lecce", "Lecce");
	data_13_6 = new Option("Taranto", "Taranto");
	
	// Sardegna
	data_14 = new Option("Sardegna", "Sardegna");
	//Provincie
	data_14_1 = new Option("Cagliari", "Cagliari");
	data_14_2 = new Option("Carbonia-Iglesias", "Carbonia-Iglesias");
	data_14_3 = new Option("Medio Campidano", "Medio Campidano");
	data_14_4 = new Option("Nuoro", "Nuoro");
	data_14_5 = new Option("Ogliastra", "Ogliastra");
	data_14_6 = new Option("Olbia-Tempio", "Olbia-Tempio");
	data_14_7 = new Option("Oristano", "Oristano");
	data_14_8 = new Option("Sassari", "Sassari");
	
	// Sicilia
	data_15 = new Option("Sicilia", "Sicilia");
	//Provincie
	data_15_1 = new Option("Agrigento", "Agrigento");
	data_15_2 = new Option("Caltanissetta", "Caltanissetta");
	data_15_3 = new Option("Catania", "Catania");
	data_15_4 = new Option("Enna", "Enna");
	data_15_5 = new Option("Messina", "Messina");
	data_15_6 = new Option("Palermo", "Palermo");
	data_15_7 = new Option("Ragusa", "Ragusa");
	data_15_8 = new Option("Siracusa", "Siracusa");
	data_15_9 = new Option("Trapani", "Trapani");
	data_15_10 = new Option("Palermo", "Palermo");
	
	// Toscana
	data_16 = new Option("Toscana", "Toscana");
	//Provincie
	data_16_1 = new Option("Arezzo", "Arezzo");
	data_16_2 = new Option("Firenze", "Firenze");
	data_16_3 = new Option("Grosseto", "Grosseto");
	data_16_4 = new Option("Livorno", "Livorno");
	data_16_5 = new Option("Lucca", "Lucca");
	data_16_6 = new Option("Massa e Carrara", "Massa e Carrara");
	data_16_7 = new Option("Pisa", "Pisa");
	data_16_8 = new Option("Pistoia", "Pistoia");
	data_16_9 = new Option("Prato", "Prato");
	data_16_10 = new Option("Siena", "Siena");
	
	// Trentino-Alto Adige
	data_17 = new Option("Trentino-Alto Adige", "Trentino-Alto Adige");
	//Provincie
	data_17_1 = new Option("Bolzano", "Bolzano");
	data_17_2 = new Option("Trento", "Trento");
	
	// Umbria
	data_18 = new Option("Umbria", "Umbria");
	//Provincie
	data_18_1 = new Option("Perugia", "Perugia");
	data_18_2 = new Option("Terni", "Terni");
	
	// Valle d'Aosta
	data_19 = new Option("Valle d'Aosta", "Valle d'Aosta");
	//Provincie
	data_19_1 = new Option("Aosta", "Aosta");
	
	// Veneto
	data_20 = new Option("Veneto", "Veneto");
	//Provincie
	data_20_1 = new Option("Belluno", "Perugia");
	data_20_2 = new Option("Padova", "Terni");
	data_20_3 = new Option("Rovigo", "Terni");
	data_20_4 = new Option("Treviso", "Terni");
	data_20_5 = new Option("Venezia", "Terni");
	data_20_6 = new Option("Verona", "Terni");
	data_20_7 = new Option("Vicenza", "Terni");


    displaywhenempty=""
    valuewhenempty=-1

    displaywhennotempty=""
    valuewhennotempty=0

function change(currentbox) {
	numb = currentbox.id.split("_");
	currentbox = numb[1];

    i=parseInt(currentbox)+1

// I empty all combo boxes following the current one

    while ((eval("typeof(document.getElementById(\"combo_"+i+"\"))!='undefined'")) &&
           (document.getElementById("combo_"+i)!=null)) {
         son = document.getElementById("combo_"+i);
	     // I empty all options except the first one (it isn't allowed)
	     for (m=son.options.length-1;m>0;m--) son.options[m]=null;
	     // I reset the first option
	     son.options[0]=new Option(displaywhenempty,valuewhenempty)
	     i=i+1
    }

// now I create the string with the "base" name ("stringa"), ie. "data_1_0"
// to which I'll add _0,_1,_2,_3 etc to obtain the name of the combo box to fill

    stringa='data'
    i=0
    while ((eval("typeof(document.getElementById(\"combo_"+i+"\"))!='undefined'")) &&
           (document.getElementById("combo_"+i)!=null)) {
           eval("stringa=stringa+'_'+document.getElementById(\"combo_"+i+"\").selectedIndex")
           if (i==currentbox) break;
           i=i+1
    }

// filling the "son" combo (if exists)

    following=parseInt(currentbox)+1

    if ((eval("typeof(document.getElementById(\"combo_"+following+"\"))!='undefined'")) &&
       (document.getElementById("combo_"+following)!=null)) {
       son = document.getElementById("combo_"+following);
       stringa=stringa+"_"
       i=0
       while ((eval("typeof("+stringa+i+")!='undefined'")) || (i==0)) {

       // if there are no options, I empty the first option of the "son" combo
	   // otherwise I put "-select-" in it

	   	  if ((i==0) && eval("typeof("+stringa+"0)=='undefined'"))
	   	      if (eval("typeof("+stringa+"1)=='undefined'"))
	   	         eval("son.options[0]=new Option(displaywhenempty,valuewhenempty)")
	   	      else
	             eval("son.options[0]=new Option(displaywhennotempty,valuewhennotempty)")
	      else
              eval("son.options["+i+"]=new Option("+stringa+i+".text,"+stringa+i+".value)")
	      i=i+1
	   }
       //son.focus()
       i=1
       combostatus=''
       cstatus=stringa.split("_")
       while (cstatus[i]!=null) {
          combostatus=combostatus+cstatus[i]
          i=i+1
          }
       return combostatus;
    }
}

//-->
