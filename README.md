# Semestrálne zadanie WEBTECH2

Github semestrálneho zadania predmetu Webové technológie 2 na FEI STU. Úlohou zadania bolo naprogramovať aplikáciu pre generovanie a vypracovávanie matematických úloh.

## Inštalácia

Projekt je možné spustiť ako docker balíček spustením súboru [docker-compose.yml](./docker-compose.yml).

```bash
docker-compose up --build
```

Pre úspešné skompilovanie súboru je potrebné mať nainštalovanú a spustenú službu [docker](https://www.docker.com/). Po spustení sa vytvorí docker **kontajner s aplikáciou** a rovnako aj **kontajner s databázou**. Pre tento účel bola použitá databáza mySQL.
Pri inicializácii databázy sa automaticky vytvoria dvaja používatelia, ktorých dáta sú spomenuté v sekcii [Použitie](#usage)

Druhou možnosťou je použitie webtech2 servera na linke
https://site104.webte.fei.stuba.sk/SZ

### Prístupy

|  | Server | Docker |
|----------|----------|----------|
| **hostname** | localhost | mysql | 
| **username** | xhlacina | web | 
| **password** | HRgY2Y7hHesuNaZ | web | 
| **dbname** | semestralne | webtech2 | 

## Použitie

Pre testovanie prihlásenia bol vytvorený používateľ pre každú rolu. Ich údaje sú popísané v nasledujúcej tabulke:
| **id** | **name**   | **surname** | **password** | **email**    | **role**     |
|----|--------|---------|----------|----------|----------|
| 4  | Peter  | Mares   | pass     | teacher  | Ucitel |
| 5  | Jozef  | Vajda   | pass     | student  | Student |

Každá rola má možnosť pristupovať len k ich na to určenej časti aplikácie. Je potrebné doplnenie prístupov k ďalším novovytvoreným podstránkam.

### Validation API

Beží na adrese https://site104.webte.fei.stuba.sk:9001/ na základe [FastAPI](https://fastapi.tiangolo.com/) kniznice pre python. Ak tento server nie je spustený je potrené dostať sa dostať na adresu jej použitia v projekte a manuálne ju spustiť.

Adresa z root:
```bash
cd /var/www/site104.webte.fei.stuba.sk/FastApi/
```

Spustenie:
```bash
uvicorn main:app --host 0.0.0.0 --port 9001 --ssl-keyfile /home/xhlacina/certs/webte.fei.stuba.sk.key --ssl-certfile /home/xhlacina/certs/webte_fei_stuba_sk.pem
```

Táto api automaticky parsuje latex tagy, ktoré na vstupe očakáva. Po spracovaní vstupov je vrátená hodnota 1/0 podľa správnosti prijatého výsledku a správneho výsledku.

Príklad requestu:
```http
POST /compare HTTP/1.1
Host: https://site104.webte.fei.stuba.sk:9001/
Content-Type: application/json

{
  "expr1": "\expr 4",
  "expr2": "\expr 8/2"
}
```

## Licencia

Tento projekt je autorský projekt a jeho distribúcia nie je povolená bez zvolenia autorov.

## Distribúcia

Projekt je dostupný ako public repozitár na [linku](https://github.com/xhlacina/SZ_Webtech2). Speciálne povolenie na distribúciu udelujeme **doc. Ing. Kataríne Žákovej, PhD.**

## Rozdelenie úloh

**Vladimír:**
- [x] prihlasovanie sa do aplikácie (študent, učiteľ) + Basic GUI of whole app
- [x] Kontrola správnosti výsledku - API
- [x] Docker balíček

**Martin:**
- [x] Kontrola správnosti výsledku - in APP
- [x] GUI a funkcionalita učiteľa
- [x] Export do csv a pdf

Adam:
- [x] GUI a funkcionalita študenta (vrátane matematického editora)

Dominik:
- [x] dvojjazyčnosť
- [x] Návody
- [x] Video

- [x] Používanie verzionovacieho systému všetkými členmi tímu
- [x] Finalizácia aplikácie

## Vypracovali

-   [Vladimír Hlačina](https://github.com/xhlacina)
-   [Martin Krivošík](https://github.com/MartinKrivosik)
-   [Adam Augustín](https://github.com/DWitchKing)
-   [Dominik Šmidák](https://github.com/DominikSmidak)