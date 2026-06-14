export type LanguageCode = 'es' | 'qu' | 'ash';

export interface UserState {
  step: string;
  lang?: LanguageCode;
  data: Record<string, string>;
  lastIntent?: 'orientation' | 'human' | 'request' | 'tracking' | 'general';
}

export interface ConversationTurn {
  role: 'user' | 'assistant';
  body: string;
}

export interface BotTexts {
  welcome: string;
  fallback: string;
  scope: string;
  officeHours: string;
  processServices: string;
  officialChannelsTitle: string;
  contactsTitle: string;
  backToMenu: string;
  humanContactHint: string;
  invalidName: string;
  invalidPhone: string;
  invalidTopic: string;
  invalidMessage: string;
  noSpecificOrientation: string;
  needMoreDetail: string;
  noInformation: string;
  offTopicMessage: string;
  informalMessage: string;
  trackingStatusTitle: string;
  trackingInstitution: string;
  trackingStatus: string;
  trackingDate: string;
  menu: string;
  infoMenu: string;
  needPrompt: string;
  humanName: string;
  humanPhone: string;
  humanTopic: string;
  humanMessage: string;
  humanSaved: string;
  error: string;
  reqRepName: string;
  reqRepDni: string;
  reqInstName: string;
  reqInstType: string;
  reqLocation: string;
  reqDesc: string;
  reqSaved: string;
  trackPrompt: string;
  trackNotFound: string;
}
