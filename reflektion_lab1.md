1. Vad tror Du vi har för skäl till att spara det skrapade datat i JSON-format?
  * JSON är en utbredd standard som är lätt att läsa av människor och kan användas av många applikationer utan mycket förändring. Men kanske viktigast av allt: för att Douglas Crockford säger så.

2. Olika jämförelsesiter är flitiga användare av webbskrapor. Kan du komma på fler typer av tillämplingar där webbskrapor förekommer?
  * Sökmotorer använder det för att se vad man har för innehåll på en sida. Använder man mikroformat kan innehållet lättare skrapas och kategoriseras. Man kan även skrapa om man vill skapa statistik över olika saker. Man kan skrapa för att skapa en tumnagelbild för en sida som kan användas vid en länk.

3. Hur har du i din skrapning underlättat för serverägaren?
  * Jag har identifierat mig med mitt användarnamn och jag har endast skrapat första sidan under utvecklingen. Skulle jag göra detta på en annan sida skulle jag även be om lov först eller se till att det är tillåtet.

4. Vilka etiska aspekter bör man fundera kring vid webbskrapning?
  * Man kanske kommer över information som människor har råkat göra publik utan att de är medvetna om det. Då bör man vara försiktig med hur man publicerar det vidare.

5. Vad finns det för risker med applikationer som innefattar automatisk skrapning av webbsidor? Nämn minst ett par stycken!
  * Sidor kan ändras eller försvinna så man måste implementera felhantering. De som äger sidorna kan lära sig vad som skrapas och manipulera detta genom att skicka med annan information. Om skrapningen tar för många resurser kan man skada sidorna.

6. Tänk dig att du skulle skrapa en sida gjord i ASP.NET WebForms. Vad för extra problem skulle man kunna få då?
  * Sidorna skulle vara dynamiska och beroende av postbacks vilket innebär att man inte skulle få med all innehåll.

7. Välj ut två punkter kring din kod du tycker är värd att diskutera vid redovisningen. Det kan röra val du gjort, tekniska lösningar eller lösningar du inte är riktigt nöjd med.
  1. Jag lyckades inte få PHP att förstå HTML5 och fick istället stänga av varningarna som man får. Jag försökte implementera en lösning från GitHub men lyckades inte få det att fungera.
  2. Jag tyckte det var väldigt smidigt att göra om datat till json samt hämta tillbaka det. Detta underlättade även med chache som jag trodde skulle vara mycket svårare att implementera.

8. Hitta ett rättsfall som handlar om webbskrapning. Redogör kort för detta.
  * 13 maj, 2014 [fastställde EU-domstolen](http://online.wsj.com/articles/SB10001424052702303851804579559280623224964) att europeiska medborgare har [rätt att glömmas](http://en.wikipedia.org/wiki/Right_to_be_forgotten). Detta innebär att sökmotorer som Google kan tvingas ta bort information från sina sökresultat om någon inte vill att det ska vara där. Detta innebär att sökmotorerna måste hitta en ballans mellan rätten att glömmas och rätten till offentlig information.

9. Känner du att du lärt dig något av denna uppgift?
  * Jag ha lärt mig väldigt mycket om webbskrapning och jag har redan några idéer om användningsfall. Jag har även lärt mig lite om JSON i PHP samt Curl.
