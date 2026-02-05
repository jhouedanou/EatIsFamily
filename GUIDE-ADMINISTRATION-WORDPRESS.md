# Guide d'Administration WordPress - EatIsFamily

## Table des matieres

1. [Connexion a WordPress](#1-connexion-a-wordpress)
2. [Vue d'ensemble du tableau de bord EatIsFamily](#2-vue-densemble-du-tableau-de-bord-eatisfamily)
3. [Gerer les Custom Post Types (CPT)](#3-gerer-les-custom-post-types-cpt)
   - [3.1 Activities (Activites)](#31-activities-activites)
   - [3.2 Events (Evenements)](#32-events-evenements)
   - [3.3 Jobs (Offres d'emploi)](#33-jobs-offres-demploi)
   - [3.4 Venues (Lieux)](#34-venues-lieux)
   - [3.5 Timeline (Chronologie)](#35-timeline-chronologie)
   - [3.6 Articles de Blog](#36-articles-de-blog)
4. [La section EatIsFamily - Administration du contenu du site](#4-la-section-eatisfamily---administration-du-contenu-du-site)
   - [4.1 Dashboard EatIsFamily](#41-dashboard-eatisfamily)
   - [4.2 Site Content](#42-site-content)
   - [4.3 Pages Content](#43-pages-content)
   - [4.4 Forms & Labels](#44-forms--labels)
   - [4.5 Components (Navbar & Footer)](#45-components-navbar--footer)
   - [4.6 Venues / Map](#46-venues--map)
   - [4.7 Partners](#47-partners)
   - [4.8 Services](#48-services)
   - [4.9 Gallery](#49-gallery)
   - [4.10 Types d'emploi & Departements](#410-types-demploi--departements)
   - [4.11 Data Management](#411-data-management)
5. [Recuperer les entrees de formulaire avec Flamingo](#5-recuperer-les-entrees-de-formulaire-avec-flamingo)
   - [5.1 Installation de Flamingo](#51-installation-de-flamingo)
   - [5.2 Consulter les messages recus](#52-consulter-les-messages-recus)
   - [5.3 Filtrer et rechercher](#53-filtrer-et-rechercher)
   - [5.4 Exporter les donnees](#54-exporter-les-donnees)
6. [Contact Form 7 - Configuration](#6-contact-form-7---configuration)
   - [6.1 Formulaire de contact](#61-formulaire-de-contact)
   - [6.2 Formulaire de candidature](#62-formulaire-de-candidature)
7. [Depannage](#7-depannage)

---

## 1. Connexion a WordPress

1. Ouvrez votre navigateur web (Chrome, Firefox, Edge...)
2. Dans la barre d'adresse, tapez l'URL de votre site suivie de `/wp-admin`
   - Exemple : `https://votre-site.com/wp-admin`
3. Entrez votre **identifiant** (nom d'utilisateur ou email)
4. Entrez votre **mot de passe**
5. Cliquez sur **"Se connecter"**

Vous arrivez sur le **Tableau de bord WordPress**.

> **Astuce** : Cochez "Se souvenir de moi" pour rester connecte plus longtemps.

---

## 2. Vue d'ensemble du tableau de bord EatIsFamily

Une fois connecte, vous verrez dans le **menu lateral gauche** une section speciale avec une icone de fourchette/couteau :

```
EatIsFamily          (menu principal avec icone food)
  ├── Site Content        (parametres globaux du site)
  ├── Pages Content       (contenu de chaque page)
  ├── Forms & Labels      (formulaires et leurs textes)
  ├── Components          (navbar et footer)
  ├── Venues / Map        (section carte et lieux)
  ├── Partners            (logos partenaires)
  ├── Services            (services proposes)
  ├── Sustainability      (developpement durable)
  ├── Gallery             (galerie photos)
  ├── Types d'emploi      (types d'emploi et departements)
  └── Data Management     (import/export de donnees)
```

En plus de cette section, vous trouverez les **Custom Post Types** dans le menu lateral :

```
Articles              (articles de blog classiques)
Activities            (activites culinaires)
Events                (evenements)
Jobs                  (offres d'emploi)
Venues                (lieux/stades)
Timeline              (chronologie de l'entreprise)
```

---

## 3. Gerer les Custom Post Types (CPT)

Les Custom Post Types sont des types de contenu personnalises. Chaque CPT a ses propres champs specifiques.

### 3.1 Activities (Activites)

Les activites culinaires proposees (ateliers cuisine, degustation, etc.).

#### Voir la liste des activites

1. Dans le menu lateral, cliquez sur **"Activities"**
2. Vous verrez la liste de toutes les activites existantes

#### Ajouter une nouvelle activite

1. Cliquez sur **Activities > Add New** (ou le bouton "Add New" en haut de la page)
2. Remplissez les champs suivants :

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Titre** | Le nom de l'activite (champ principal en haut) | "Atelier Cuisine Italienne" |
| **Contenu** | Description detaillee de l'activite (editeur principal) | Texte riche avec mise en forme |
| **Extrait** | Resume court (encadre "Extrait" en bas) | "Decouvrez les secrets de la cuisine italienne" |
| **Image mise en avant** | Photo principale (encadre a droite) | Cliquer "Definir l'image mise en avant" |

3. Dans la section **"Activity Details"** (sous l'editeur), remplissez :

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Date & Time** | Date et heure de l'activite | 2026-03-15 14:00 |
| **Location** | Lieu de l'activite | "Culinary Studio, 123 Rue de la Cuisine, Paris" |
| **Category** | Categorie (menu deroulant) | Cuisine, Patisserie, Vins & Spiritueux, Degustation, Team Building, Atelier, Masterclass |
| **Status** | Statut de l'activite | Open for Registration, Closed, Fully Booked, Cancelled |
| **Total Capacity** | Nombre de places total | 20 |
| **Available Spots** | Places restantes | 15 |
| **Price** | Prix | "85EUR" |
| **Duration** | Duree | "3 hours" |

4. Cliquez sur **"Publier"** (ou "Mettre a jour" si vous modifiez)

#### Modifier une activite existante

1. Allez dans **Activities**
2. Passez la souris sur l'activite a modifier
3. Cliquez sur **"Modifier"**
4. Faites vos changements
5. Cliquez sur **"Mettre a jour"**

#### Supprimer une activite

1. Passez la souris sur l'activite
2. Cliquez sur **"Mettre a la corbeille"**

> **Attention** : La suppression d'une activite la retire immediatement du site frontend.

---

### 3.2 Events (Evenements)

Les evenements (partenariats avec des stades, festivals, etc.).

#### Ajouter un nouvel evenement

1. Cliquez sur **Events > Add New**
2. Remplissez :

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Titre** | Nom de l'evenement | "Paris FC vs Toulouse FC" |
| **Contenu** | Description de l'evenement | Texte riche |
| **Image mise en avant** | Photo de l'evenement | Image du stade ou de l'evenement |

3. Dans la section **"Event Details"** :

| Champ | Description | Options |
|-------|-------------|---------|
| **Event Type** | Type d'evenement (menu deroulant) | Venue Partnership, Festival, Sports Event, Fashion Event, Concert, Corporate Event |
| **Related Venue** | Lieu associe (menu deroulant dynamique) | Liste des venues enregistrees |

4. Cliquez sur **"Publier"**

---

### 3.3 Jobs (Offres d'emploi)

Les offres d'emploi affichees sur la page Careers.

#### Ajouter une offre d'emploi

1. Cliquez sur **Jobs > Add New**
2. Remplissez :

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Titre** | Intitule du poste | "Chef de Cuisine" |
| **Contenu** | Description complete du poste | Description detaillee des missions |
| **Extrait** | Resume du poste | "Rejoignez notre equipe en tant que Chef de Cuisine" |
| **Image mise en avant** | Photo du lieu de travail | Image du restaurant/cuisine |

3. Dans la section **"Job Details"** :

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Venue** | Lieu de travail (menu deroulant) | "Stade Jean-Bouin (Paris)" - se remplit dynamiquement depuis les Venues |
| **Department** | Departement (menu deroulant) | Cuisine, Service, Boissons, Operations, Qualite, Direction, Marketing, RH |
| **Job Type** | Type de contrat (menu deroulant) | Temps plein, Temps partiel, Saisonnier, Contrat, Stage |
| **Salary** | Salaire | "45 000EUR - 60 000EUR" |

4. **Requirements** (Exigences du poste) :
   - Cliquez sur **"+ Add Item"** pour ajouter un pre-requis
   - Ecrivez chaque exigence dans un champ (une par ligne)
   - Cliquez sur le **"X"** rouge pour supprimer une exigence
   - Exemple : "5 ans d'experience en cuisine gastronomique"

5. **Benefits** (Avantages) :
   - Meme fonctionnement que les Requirements
   - Exemple : "Mutuelle d'entreprise", "13e mois"

6. **Life at Venue - Image Gallery** :
   - Cliquez sur **"Add Images"** pour ajouter des photos de l'environnement de travail
   - Selectionnez des images dans la **Mediatheque** ou uploadez-en de nouvelles
   - Ces images apparaissent dans la section "Life at [Venue]" sur la page de detail du poste

7. Cliquez sur **"Publier"**

---

### 3.4 Venues (Lieux)

Les lieux/stades ou l'entreprise opere.

#### Ajouter un lieu

1. Cliquez sur **Venues > Add New**
2. Remplissez le **titre** (nom du lieu, ex: "Stade Jean-Bouin")
3. Ajoutez une **image mise en avant** (photo principale du lieu)

4. Dans la section **"Venue Details"** :

**Location Information :**

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Venue ID/Slug** | Identifiant unique (utilise dans l'API) | "stade-jean-bouin" |
| **Full Address** | Adresse complete | "Stade Jean-Bouin, Paris 16e" |
| **City** | Ville | "Paris" |
| **Country** | Pays | "France" |
| **Venue Type** | Type de lieu (menu deroulant) | Stadium, Festival, Arena |

**Map Coordinates (pour la carte interactive) :**

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Latitude** | Coordonnee latitude | 48.8414 |
| **Longitude** | Coordonnee longitude | 2.2530 |

> **Comment trouver les coordonnees ?** Allez sur Google Maps, faites un clic droit sur le lieu, et copiez les coordonnees affichees.

**Images de la Grille :**

| Champ | Description |
|-------|-------------|
| **Image Gauche** | Premiere image affichee dans la grille de details (le badge du type sera affiche dessus) |
| **Image Droite** | Deuxieme image de la grille (le bouton fermer sera affiche dessus) |

Pour chaque image : cliquez sur **"Select Image"**, choisissez dans la mediatheque ou uploadez.

**Logo de la Venue :**

| Champ | Description |
|-------|-------------|
| **Logo** | Logo du lieu, affiche a cote du nom |

**Statistics :**

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Capacity** | Capacite d'accueil | "20,000" |
| **Staff Members** | Nombre de membres de l'equipe | 150 |
| **Guests Served** | Nombre de convives servis | "15,000" |
| **Recent Event** | Dernier evenement | "Football - Paris FC vs Toulouse FC" |

**Services :**
- Cliquez sur **"+ Add Item"** pour ajouter un service
- Exemple : "Restauration VIP", "Buvettes grand public"

**Shops / Food Outlets :**
- Cliquez sur **"+ Add Shop"** pour ajouter un point de vente
- Remplissez le **Nom** et selectionnez une **Image**

**Menu Items :**
- Cliquez sur **"+ Add Menu Item"** pour ajouter un plat
- Remplissez : Nom, Prix, Description, Image

5. Cliquez sur **"Publier"**

---

### 3.5 Timeline (Chronologie)

Les evenements de la chronologie affichee sur la page "About".

#### Ajouter un evenement a la timeline

1. Cliquez sur **Timeline > Add Timeline Event**
2. Remplissez :

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Titre** | Titre de l'evenement | "Creation d'Eat Is Friday" |
| **Image mise en avant** | Image illustrative | Photo de l'epoque |

3. Dans la section **"Timeline Event Details"**, ajoutez les meta-donnees specifiques (annee, description courte, etc.)
4. Cliquez sur **"Publier"**

---

### 3.6 Articles de Blog

Les articles de blog sont geres via le systeme standard de WordPress.

#### Ajouter un article de blog

1. Cliquez sur **Articles > Ajouter**
2. Remplissez :

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Titre** | Titre de l'article | "Les tendances culinaires 2026" |
| **Contenu** | Corps de l'article | Texte riche avec images |
| **Extrait** | Resume | Court resume de l'article |
| **Image mise en avant** | Image principale | Photo illustrative |
| **Categories** | Categories WordPress classiques | Choisir ou creer |
| **Blog Categories** | Taxonomie personnalisee EatIsFamily | Choisir ou creer |

3. Dans la section **"Blog Post Details"** :

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Reading Time** | Temps de lecture | "5 min read" |
| **Author Name** | Nom de l'auteur | "Marie Dupont" |
| **Author Avatar** | Photo de l'auteur | Selectionnez une image |

4. Cliquez sur **"Publier"**

---

## 4. La section EatIsFamily - Administration du contenu du site

Cette section permet de gerer **tout le contenu textuel et visuel du site** sans toucher au code.

### 4.1 Dashboard EatIsFamily

**Chemin : Menu lateral > EatIsFamily**

C'est la page d'accueil de l'administration EatIsFamily. Elle affiche des **cartes cliquables** vers chaque section :

- **Pages Content** : Contenu de toutes les pages
- **Forms & Labels** : Formulaires et leurs textes
- **Components** : Navbar et Footer
- **Venues / Map** : Section carte interactive
- **Partners** : Logos des partenaires

Cliquez sur le bouton **"Gerer"** de chaque carte pour acceder a la section correspondante.

---

### 4.2 Site Content

**Chemin : EatIsFamily > Site Content**

Gere les **parametres globaux** du site :

- **Nom du site** et tagline
- **SEO** : Titre par defaut, description, mots-cles, image Open Graph
- **Contact** : Email, telephone
- **Reseaux sociaux** : Facebook, Instagram, Twitter, LinkedIn, YouTube

#### Comment modifier :

1. Allez dans **EatIsFamily > Site Content**
2. Modifiez les champs souhaites
3. Cliquez sur **"Enregistrer"**

---

### 4.3 Pages Content

**Chemin : EatIsFamily > Pages Content**

C'est la section **la plus importante**. Elle permet de modifier le contenu de **chaque page** du site :

- **Homepage** : Hero (titre, sous-titre, bouton CTA, image de fond)
- **About** : Hero, section d'introduction, titre timeline
- **Contact** : Hero, titre et sous-titre du formulaire
- **Careers** : Hero, titre des avantages, liste des avantages
- **Events** : Hero
- **Blog** : Sections de contenu
- **Job Detail** : Contenu de la page detail d'un poste
- Et bien d'autres pages...

#### Comment modifier le contenu d'une page :

1. Allez dans **EatIsFamily > Pages Content**
2. Utilisez les **onglets** en haut pour naviguer entre les pages
3. Modifiez les champs (texte, images, boutons)
4. Les champs avec un editeur riche (WYSIWYG) permettent de mettre en forme le texte (gras, italique, listes, liens)
5. Cliquez sur **"Enregistrer"**

> **Important** : Les modifications sont visibles immediatement sur le site frontend apres sauvegarde.

---

### 4.4 Forms & Labels

**Chemin : EatIsFamily > Forms & Labels**

Gere **tous les textes des formulaires** du site. L'interface est divisee en 4 onglets :

#### Onglet "Job Search" (Recherche d'emploi)

Labels du formulaire de recherche d'emploi sur la page d'accueil :
- Titre du formulaire
- Sous-titre
- Placeholders des champs (poste, site)
- Textes des boutons (rechercher, chargement)

#### Onglet "Contact"

Labels du formulaire de contact + **integration Contact Form 7** :

1. **Section bleue "Integration Contact Form 7"** :
   - Champ **"ID du Formulaire CF7"** : Entrez l'ID de votre formulaire CF7
   - Voir [section 6.1](#61-formulaire-de-contact) pour les details

2. **Labels et placeholders** :
   - Nom, Email, Sujet, Message
   - Texte du bouton d'envoi
   - Messages de succes et d'erreur

#### Onglet "Job Application" (Candidature)

Labels du formulaire de candidature + **integration CF7 pour les candidatures** :

1. **Section bleue "Integration Contact Form 7 - Candidatures"** :
   - Champ **"ID du Formulaire CF7"** pour les candidatures
   - Voir [section 6.2](#62-formulaire-de-candidature) pour les details

2. **Labels** : Prenom, Nom, Email, Telephone, CV, Lettre de motivation
3. **Messages** : Succes, erreur, texte du bouton

#### Onglet "Activity Registration" (Inscription activites)

Labels du formulaire d'inscription aux activites :
- Nom, Email, Telephone
- Nombre de participants
- Restrictions alimentaires
- Informations supplementaires

#### Comment sauvegarder :

1. Modifiez les champs dans n'importe quel onglet
2. Cliquez sur **"Save Forms & Labels"** en bas de page
3. Un message vert "Forms & Labels saved successfully!" apparait en haut

> **Note technique** : La sauvegarde utilise AJAX (pas de rechargement de page). Si vous obtenez une erreur 403, c'est normal - le systeme contourne cette restriction automatiquement.

---

### 4.5 Components (Navbar & Footer)

**Chemin : EatIsFamily > Components**

Gere les composants communs a toutes les pages :

#### Navbar (barre de navigation)

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Brand Name** | Premiere partie du nom | "Eat Is" |
| **Brand Highlight** | Partie en surbrillance | "Friday" |
| **CTA Desktop** | Texte du bouton (desktop) | "Contact Us" |
| **CTA Mobile** | Texte du bouton (mobile) | "Contact" |
| **Liens de navigation** | About, Activities, Events, Careers, Blog, Contact | Modifiable |

#### Footer (pied de page)

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Logo Footer** | URL du logo du footer | Selectionnez via "Select Image" |
| **Brand Name** | Nom de la marque | "Eat Is Friday" |
| **Brand Description** | Description courte | "Votre partenaire restauration..." |
| **Contact Email** | Email de contact | "contact@eatisfamily.fr" |
| **Contact Phone** | Telephone | "+33 1 XX XX XX XX" |
| **Company Title** | Titre colonne "Company" | "Company" |
| **Policy Title** | Titre colonne "Policy" | "Policy" |
| **Copyright** | Texte de copyright | "(c) {year} Eat Is Friday. All rights reserved." |
| **Back to Top** | Texte du bouton retour haut | "Back to top" |

**Liens du footer** :
- **Company Links** : Liens vers les pages principales (About, Careers, etc.)
  - Cliquez **"+ Add"** pour ajouter un lien
  - Remplissez **Texte** et **URL** (ex: "About" -> "/about")
- **Policy Links** : Liens vers les pages legales
  - Meme fonctionnement (ex: "Privacy Policy" -> "/privacy")

---

### 4.6 Venues / Map

**Chemin : EatIsFamily > Venues / Map**

Gere la section **"Explore Where We Operate"** avec la carte interactive :

#### Metadata

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Title** | Titre de la section | "Explore Where We Operate" |
| **Description** | Description sous le titre | "Decouvrez nos sites..." |
| **Filter Label** | Label du filtre | "Click to filter by venue type" |

#### Types de lieux (Venue Types)

Jusqu'a 10 types de lieux configurables :

Pour chaque type :
| Champ | Description | Exemple |
|-------|-------------|---------|
| **ID** | Identifiant unique | "stadium" |
| **Name** | Nom affiche | "Stadium" |
| **Image** | Image du type | Selectionnez via "Select Image" |
| **Map Icon** | Icone sur la carte | Selectionnez une icone |

#### Statistiques (Stats)

Jusqu'a 8 statistiques affichees :
| Champ | Description | Exemple |
|-------|-------------|---------|
| **Value** | Valeur chiffree | "20+" |
| **Label** | Description | "Venues across France" |

---

### 4.7 Partners

**Chemin : EatIsFamily > Partners**

Gere les **logos des partenaires** affiches sur la page d'accueil.

1. Cliquez sur **"Add Partner"** ou **"Select Image"**
2. Uploadez ou selectionnez le logo du partenaire
3. Cliquez sur **"Enregistrer"**

Pour supprimer un partenaire, cliquez sur le **"X"** rouge sur son logo.

---

### 4.8 Services

**Chemin : EatIsFamily > Services**

Gere la liste des services proposes par l'entreprise, affiches sur le site.

---

### 4.9 Gallery

**Chemin : EatIsFamily > Gallery**

Gere la galerie photos du site.

1. Cliquez sur **"Add Images"**
2. Selectionnez ou uploadez des photos
3. Rearrangez l'ordre si necessaire
4. Cliquez sur **"Enregistrer"**

---

### 4.10 Types d'emploi & Departements

**Chemin : EatIsFamily > Types d'emploi**

Gere les **categories d'emploi** utilisees dans les fiches de poste :

**Types d'emploi** (contrat) :
- Temps plein
- Temps partiel
- Saisonnier
- Contrat
- Stage

**Departements** :
- Cuisine
- Service
- Boissons
- Operations
- Qualite
- Direction
- Marketing
- Ressources Humaines

Vous pouvez ajouter, modifier ou supprimer des types et departements. Ils apparaitront automatiquement dans les menus deroulants lors de la creation d'offres d'emploi.

---

### 4.11 Data Management

**Chemin : EatIsFamily > Data Management**

Permet d'**importer ou reimporter** les donnees depuis les fichiers JSON de base :

- **Reimporter pages-content.json** : Recharge le contenu des pages depuis le fichier de donnees
- **Reimporter site-content.json** : Recharge les parametres globaux

> **ATTENTION** : La reimportation **ecrase** les modifications que vous avez faites dans l'admin. Utilisez cette fonction uniquement si vous souhaitez repartir de zero.

---

## 5. Recuperer les entrees de formulaire avec Flamingo

**Flamingo** est un plugin WordPress gratuit qui **stocke tous les messages** envoyes via Contact Form 7 directement dans la base de donnees WordPress. Sans Flamingo, les messages sont uniquement envoyes par email et ne sont pas conserves dans WordPress.

### 5.1 Installation de Flamingo

Si Flamingo n'est pas encore installe :

1. Allez dans **Extensions > Ajouter**
2. Dans la barre de recherche, tapez **"Flamingo"**
3. Trouvez le plugin **"Flamingo"** par Takayuki Miyoshi (le meme auteur que Contact Form 7)
4. Cliquez sur **"Installer maintenant"**
5. Une fois installe, cliquez sur **"Activer"**

Un nouveau menu **"Flamingo"** apparait dans le menu lateral gauche de WordPress.

> **Prerequis** : Contact Form 7 doit etre installe et actif. Flamingo fonctionne en complement de CF7.

---

### 5.2 Consulter les messages recus

#### Voir tous les messages du formulaire de contact

1. Dans le menu lateral, cliquez sur **"Flamingo"**
2. Cliquez sur **"Inbound Messages"** (Messages entrants)
3. Vous verrez un tableau avec tous les messages recus :

| Colonne | Description |
|---------|-------------|
| **Subject** | Sujet du message (ou titre du formulaire CF7 utilise) |
| **From** | Nom et email de l'expediteur |
| **Date** | Date et heure de reception |
| **Status** | Lu/Non lu |

4. **Cliquez sur un message** pour voir tous les details :
   - Nom complet
   - Email
   - Type d'evenement (pour le formulaire de contact)
   - Lieu
   - Date de l'evenement
   - Nombre d'invites
   - Message complet
   - **Pour les candidatures** : Nom, email, telephone, LinkedIn, poste, lieu, CV (fichier joint)

#### Voir le carnet d'adresses

1. Cliquez sur **Flamingo > Address Book** (Carnet d'adresses)
2. Vous verrez la liste de **toutes les personnes** qui ont envoye un message
3. Chaque entree contient : nom, email, historique des messages

---

### 5.3 Filtrer et rechercher

#### Filtrer par formulaire

1. Dans **Flamingo > Inbound Messages**
2. Utilisez le menu deroulant **"Channel"** (Canal) en haut
3. Selectionnez le formulaire specifique :
   - **"Contact"** : Messages du formulaire de contact
   - **"Candidature"** : Candidatures aux offres d'emploi
4. Cliquez sur **"Filter"** (Filtrer)

#### Rechercher un message

1. Utilisez la **barre de recherche** en haut a droite
2. Tapez un mot-cle (nom, email, mot dans le message)
3. Appuyez sur **Entree** ou cliquez sur "Rechercher"

#### Filtrer par statut

- **Tous** : Tous les messages
- **Non lus** : Messages pas encore consultes
- **Spam** : Messages marques comme spam

---

### 5.4 Exporter les donnees

Flamingo ne propose pas d'export CSV natif, mais vous pouvez utiliser un plugin complementaire :

#### Methode 1 : Plugin "Flamingo CSV Exporter" (recommande)

1. Allez dans **Extensions > Ajouter**
2. Recherchez **"Flamingo CSV"** ou **"CF7 Flamingo CSV"**
3. Installez et activez le plugin
4. Retournez dans **Flamingo > Inbound Messages**
5. Un bouton **"Export CSV"** apparait
6. Cliquez dessus pour telecharger un fichier CSV avec toutes les entrees

#### Methode 2 : Copier-coller manuel

1. Ouvrez chaque message dans Flamingo
2. Copiez les informations dans un tableur (Excel, Google Sheets)

#### Methode 3 : Export via la base de donnees

Pour les utilisateurs avances, les donnees sont stockees dans les tables WordPress standard (wp_posts avec le type `flamingo_inbound`).

---

## 6. Contact Form 7 - Configuration

### 6.1 Formulaire de contact

#### Verifier que le formulaire existe

1. Allez dans **Contact > Formulaires de contact**
2. Vous devriez voir un formulaire de contact configure
3. Notez le **shortcode** affiche, par exemple : `[contact-form-7 id="123" title="Contact"]`
4. Le numero **123** est l'**ID du formulaire**

#### Structure du formulaire de contact

Le formulaire de contact doit contenir ces champs CF7 :

```
[text* your-name placeholder "Votre nom"]
[email* your-email placeholder "Votre email"]
[text event-type placeholder "Type d'evenement"]
[text location placeholder "Lieu"]
[date event-date placeholder "Date de l'evenement"]
[text guests placeholder "Nombre d'invites"]
[textarea* your-message placeholder "Votre message"]
[submit "Envoyer"]
```

#### Correspondance des champs

| Champ sur le site | Champ CF7 |
|-------------------|-----------|
| Nom | `your-name` |
| Email | `your-email` |
| Type d'evenement | `event-type` |
| Lieu | `location` |
| Date | `event-date` |
| Nombre d'invites | `guests` |
| Message | `your-message` |

#### Configurer l'email

1. Cliquez sur votre formulaire pour l'editer
2. Allez dans l'onglet **"Mail"**
3. Configurez :
   - **De** : `[your-name] <[your-email]>`
   - **A** : votre email de reception
   - **Objet** : `Nouvelle demande de contact - [event-type]`
   - **Corps du message** :
     ```
     Nom: [your-name]
     Email: [your-email]
     Type d'evenement: [event-type]
     Lieu: [location]
     Date: [event-date]
     Nombre d'invites: [guests]

     Message:
     [your-message]
     ```

#### Lier le formulaire a EatIsFamily

1. Copiez l'**ID du formulaire** (ex: 123)
2. Allez dans **EatIsFamily > Forms & Labels**
3. Cliquez sur l'onglet **"Contact"**
4. Dans la section bleue **"Integration Contact Form 7"**
5. Collez l'ID dans le champ **"ID du Formulaire CF7"**
6. Cliquez sur **"Save Forms & Labels"**

---

### 6.2 Formulaire de candidature

#### Structure du formulaire de candidature

Creez un **nouveau formulaire** dans Contact Form 7 avec ces champs :

```
[text* your-name placeholder "Votre nom complet"]
[email* your-email placeholder "Votre adresse email"]
[tel* your-phone placeholder "+33 6 00 00 00 00"]
[url your-linkedin placeholder "https://linkedin.com/in/votreprofil"]
[file* your-resume limit:5mb filetypes:pdf|doc|docx]
[textarea your-message placeholder "Lettre de motivation..."]
[hidden job-title default:get]
[hidden job-location default:get]
[hidden job-slug default:get]
[submit "Envoyer ma candidature"]
```

> **Important** : Les champs `[hidden ... default:get]` sont essentiels ! Ils permettent de recevoir automatiquement le titre du poste, le lieu et le slug depuis le frontend.

#### Correspondance des champs

| Champ sur le site | Champ CF7 |
|-------------------|-----------|
| Nom complet | `your-name` |
| Email | `your-email` |
| Telephone | `your-phone` |
| LinkedIn | `your-linkedin` |
| CV (fichier) | `your-resume` |
| Lettre de motivation | `your-message` |
| Titre du poste (auto) | `job-title` |
| Lieu du poste (auto) | `job-location` |
| Slug du poste (auto) | `job-slug` |

#### Configurer l'email de candidature

Dans l'onglet **"Mail"** du formulaire :
- **Objet** : `Nouvelle candidature - [job-title]`
- **Corps** :
  ```
  Nouvelle candidature recue pour le poste : [job-title]

  Nom complet : [your-name]
  Email : [your-email]
  Telephone : [your-phone]
  LinkedIn : [your-linkedin]

  Poste : [job-title]
  Lieu : [job-location]
  Reference : [job-slug]

  Lettre de motivation :
  [your-message]
  ```
- **File Attachments** (Pieces jointes) : `[your-resume]`

#### Lier le formulaire de candidature

1. Notez l'**ID du formulaire** de candidature (ex: 456)
2. Allez dans **EatIsFamily > Forms & Labels**
3. Cliquez sur l'onglet **"Job Application"**
4. Dans la section bleue **"Integration Contact Form 7 - Candidatures"**
5. Entrez l'ID dans le champ **"ID du Formulaire CF7"**
6. Cliquez sur **"Save Forms & Labels"**

---

## 7. Depannage

### Le formulaire ne s'envoie pas

1. Verifiez que **Contact Form 7** est installe et active (Extensions > Extensions installees)
2. Verifiez que l'**ID du formulaire** est correct dans EatIsFamily > Forms & Labels
3. Testez en envoyant un message depuis le site
4. Verifiez les **logs d'erreur** WordPress (si vous y avez acces)

### Les emails ne sont pas recus

1. Verifiez votre **dossier spam/courrier indesirable**
2. Installez le plugin **"WP Mail SMTP"** pour configurer l'envoi d'emails via SMTP :
   - Allez dans **Extensions > Ajouter**
   - Recherchez "WP Mail SMTP"
   - Installez et configurez avec les parametres de votre hebergeur
3. Testez l'envoi d'email via WP Mail SMTP > Tools > Email Test

### Le fichier CV n'est pas recu dans les candidatures

1. Verifiez les limites PHP de votre hebergeur :
   - `upload_max_filesize` doit etre au moins `5M`
   - `post_max_size` doit etre au moins `8M`
2. Verifiez que `[your-resume]` est bien dans la section **"File Attachments"** de l'onglet Mail dans CF7
3. Verifiez les permissions du dossier `wp-content/uploads/wpcf7_uploads/`

### Les modifications EatIsFamily ne s'affichent pas sur le site

1. Verifiez que vous avez bien clique sur **"Enregistrer"** ou **"Save"**
2. **Videz le cache** :
   - Cache WordPress (si vous avez un plugin de cache comme WP Super Cache ou W3 Total Cache)
   - Cache du navigateur : Ctrl+Shift+R (ou Cmd+Shift+R sur Mac)
3. Verifiez que le frontend Nuxt est bien connecte a l'API WordPress

### Erreur 403 lors de la sauvegarde

C'est souvent cause par **mod_security** sur le serveur. Le theme gere automatiquement ce probleme en utilisant l'envoi AJAX avec encodage base64. Si le probleme persiste :

1. Contactez votre hebergeur pour desactiver mod_security pour l'admin WordPress
2. Ou ajoutez dans `.htaccess` :
   ```
   <IfModule mod_security.c>
   SecFilterEngine Off
   SecFilterScanPOST Off
   </IfModule>
   ```

### Flamingo n'affiche pas les messages

1. Verifiez que **Contact Form 7** est actif
2. Verifiez que **Flamingo** est actif
3. Flamingo ne stocke que les messages **envoyes apres** son activation. Les messages anterieurs ne sont pas retroactifs
4. Envoyez un message test depuis le formulaire de contact du site

### La carte interactive ne fonctionne pas

1. Verifiez que les **coordonnees GPS** (latitude/longitude) sont correctes dans chaque Venue
2. Verifiez que les types de lieux sont bien configures dans **EatIsFamily > Venues / Map**
3. Verifiez que les Venues sont publiees (statut "Publie" et non "Brouillon")

---

## Resume des actions les plus courantes

| Tache | Ou aller |
|-------|----------|
| Ajouter une offre d'emploi | Jobs > Add New |
| Modifier un evenement | Events > clic sur l'evenement |
| Ajouter une activite | Activities > Add New |
| Changer le texte de la homepage | EatIsFamily > Pages Content > Homepage |
| Modifier les liens du footer | EatIsFamily > Components > Footer |
| Voir les messages de contact | Flamingo > Inbound Messages |
| Voir les candidatures | Flamingo > Inbound Messages > Filtrer par "Candidature" |
| Ajouter un partenaire | EatIsFamily > Partners |
| Modifier les types de lieux | EatIsFamily > Venues / Map |
| Changer l'email de contact | EatIsFamily > Site Content > Contact |
| Ajouter des photos a la galerie | EatIsFamily > Gallery |

---

*Guide genere pour le theme EatIsFamily v5.1 - WordPress 6.0+*
