// Importe la fonction startStimulusApp du bon paquet npm
import { startStimulusApp } from '@symfony/stimulus-bridge'; // <-- CORRIGÉ ICI

// Initialise l'application Stimulus
const app = startStimulusApp();

// Enregistrez ici tous les contrôleurs personnalisés ou tiers
// Par exemple :
// import MyController from './controllers/MyController';
// app.register('my-controller', MyController);