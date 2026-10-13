import './bootstrap';
import './theme';
import './ai-photo-status';

// Alpine.js is already loaded by Livewire, so we don't need to import it again
// This prevents the "multiple instances of Alpine" warning

import './dialogs';
import { installApplicationDialog } from './application-dialog';
installApplicationDialog(window, document);

import './payment-waiting';

import { installToasts } from './toasts';
installToasts(window, document);

import './cv-workspace';

import './cover-letter-detail';

import './ai-studio';

import './payment-workspace';

import './survey-workspace';

import './dashboard-welcome';

import "./admin-email-workspace";

import './admin-payments-workspace';

import './job-detail-tabs';
