# Aplikacioni për Kërkimin e Ilaçeve

Ky është një aplikacion i ndërtuar me Laravel për kërkimin dhe menaxhimin e të dhënave të ilaçeve duke përdorur API-në e OpenFDA. Aplikacioni lejon përdoruesit të kërkojnë ilaçe sipas kodeve NDC, të ruajnë rezultatet në një bazë të dhënash, të eksportojnë të dhënat në formatin CSV dhe të fshijnë ilaçe të ruajtura.

## Udhëzime Instalimi

Për të instaluar dhe ekzekutuar aplikacionin në mjedisin tuaj lokal, ndiqni hapat e mëposhtëm:

1. **Klononi repozitorin**:
   ```bash
   git clone 'https://github.com/enkelosmani/pharmacy.git'
   cd pharmacy
   ```

2. **Instaloni varësitë**:
   Sigurohuni që të keni instaluar Composer dhe Node.js. Më pas, ekzekutoni:
   ```bash
   composer install
   npm install
   npm run dev
   ```

3. **Konfiguroni mjedisin**:
   - Kopjoni skedarin `.env.example` në `.env`:
     ```bash
     cp .env.example .env
     ```
   - Përditësoni skedarin `.env` me kredencialet e bazës së të dhënave dhe çelësin e aplikacionit:
     ```bash
     php artisan key:generate
     ```

4. **Krijoni lidhjen e storage**:
   Për të mundësuar aksesin në skedarët e eksportuar (si CSV), krijoni lidhjen simbolike të storage:
   ```bash
   php artisan storage:link
   ```

5. **Ekzekutoni migrimet**:
   Krijoni tabelat e nevojshme në bazën e të dhënave:
   ```bash
   php artisan migrate
   ```

6. **Nisni serverin**:
   Nisni serverin lokal të Laravel:
   ```bash
   php artisan serve
   ```
   Aplikacioni do të jetë i disponueshëm në `http://localhost:8000`.

7. **Përdorni Laravel Breeze për autentikim**:
   Laravel Breeze është përdorur për autentikim. Për të hyrë në funksionalitetet e kërkimit dhe menaxhimit të ilaçeve, regjistrohuni ose identifikohuni përmes ndërfaqes së aplikacionit.

8. **Konfigurimi i Tailwind CSS**:
   Tailwind CSS është instaluar përmes npm dhe përdoret për stilizimin e ndërfaqes. Sigurohuni që të ekzekutoni `npm run dev` për të përpiluar asetet.

## Përshkrim i Shkurtër i Logjikës së Implementuar

Aplikacioni është ndërtuar duke përdorur Laravel Livewire për ndërveprime dinamike në ndërfaqen e përdoruesit dhe integrohet me API-në e OpenFDA për të marrë të dhëna të ilaçeve. Logjika kryesore përfshin:

- **Kërkimi i ilaçeve**:
  - Përdoruesi fut një ose më shumë kode NDC (të ndara me presje) përmes një formulari.
  - Komponenti `DrugSearch` (Livewire) kontrollon autentikimin dhe vlefshmërinë e input-it.
  - Shërbimi `DrugSearchService` kontrollon fillimisht bazën e të dhënave lokale për kodet NDC. Nëse nuk gjenden, ai bën një kërkesë HTTP në API-në e OpenFDA për të marrë të dhënat dhe i ruan ato në bazën e të dhënave (`Drug` model).
  - Rezultatet shfaqen në një tabelë me informacione si kodi NDC, emri i markës, emri gjenerik, prodhuesi dhe lloji i produktit.

- **Eksportimi në CSV**:
  - Rezultatet e kërkimit mund të eksportohen në një skedar CSV përmes metodës `exportToCsv` në `DrugSearchService`. Skedari ruhet në storage dhe shkarkohet automatikisht.

- **Fshirja e ilaçeve**:
  - Përdoruesit e autentikuar mund të fshijnë ilaçe nga baza e të dhënave përmes metodës `deleteDrug` në `DrugSearch`.

- **Autentikimi**:
  - Laravel Breeze menaxhon regjistrimin, identifikimin dhe daljen e përdoruesve.
  - Vetëm përdoruesit e autentikuar mund të kryejnë kërkime, eksportime dhe fshirje.

- **Paginimi**:
  - Ilaçet e ruajtura në bazën e të dhënave shfaqen me paginim (10 për faqe) përmes Livewire's `WithPagination`.

- **Ndërfaqja**:
  - Ndërfaqja është ndërtuar me Blade dhe Tailwind CSS, duke përfshirë një pamje responsive dhe intuitive.

## Ide për Përmirësime ose Funksionalitete Shtesë

1. **Filtër dhe Kërkim i Avancuar**:
   - Shtoni filtra për të kërkuar ilaçe sipas emrit të markës, emrit gjenerik ose prodhuesit, përveç kodit NDC.
   - Implementoni një funksion kërkimi të avancuar për të kombinuar disa kritere.

2. **Cache për API**:
   - Përdorni cache (p.sh., Laravel Cache ose Redis) për të ruajtur përkohësisht përgjigjet e API-së së OpenFDA për të reduktuar numrin e kërkesave dhe për të përmirësuar performancën.

3. **Validimi i Kodeve NDC**:
   - Shtoni validim më të rreptë për formatin e kodeve NDC (p.sh., kontroll për gjatësinë dhe formatin e saktë) për të parandaluar gabime.

4. **Historiku i Kërkimeve**:
   - Implementoni një funksion për të ruajtur historikun e kërkimeve të përdoruesve të autentikuar dhe për të lejuar rikthimin e tyre.

5. **Ndërfaqe e Përmirësuar**:
   - Shtoni modal ose njoftime (p.sh., me SweetAlert) për konfirmimin e fshirjes së ilaçeve.
   - Përmirësoni dizajnin me animacione dhe stile shtesë të Tailwind CSS për një përvojë më tërheqëse.

6. **Mbështetje për Multi-Gjuhësi**:
   - Shtoni mbështetje për gjuhë të tjera (p.sh., anglisht) duke përdorur Laravel Localization për të zgjeruar audiencën.
   - 
7. **Testimi**:
   - Shtoni teste automatike (p.sh., PHPUnit për backend dhe Dusk për ndërfaqen) për të siguruar stabilitetin e aplikacionit.

8. **Eksportime të Ndryshme**:
   - Lejo eksportimin në formate të tjera si PDF ose Excel, përveç CSV, për të përmbushur nevoja të ndryshme të përdoruesve.

9. **Roli dhe Autorizimi**:
    - Implementoni role të përdoruesve (p.sh., admin, përdorues i rregullt) për të kufizuar veprimet si fshirja e ilaçeve vetëm për administratorët.

## Varësi të Jashtme

- **Laravel Breeze**: Për autentikim dhe regjistrim.
- **Tailwind CSS**: Për stilizimin e ndërfaqes.
- **Livewire**: Për ndërveprime dinamike pa JavaScript të personalizuar.
- **OpenFDA API**: Për marrjen e të dhënave të ilaçeve.

## Shënime

- Sigurohuni që të keni një lidhje interneti për të aksesuar API-në e OpenFDA.
- Kontrolloni konfigurimin e storage (`storage_path('app/public')`) për të siguruar që skedarët CSV mund të krijohen dhe aksesohen.
