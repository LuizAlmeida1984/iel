import { bootstrapApplication } from '@angular/platform-browser';
import { appConfig } from './app/app.config';
import { App } from './app/app';

const banner = `%c
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
@@@@@@                                                              @@@@@@
@@@@@@   @@@    @@@@@@@@   @                                        @@@@@@
@@@@@@   @@@    @@@@@@@@   @                                        @@@@@@
@@@@@@    @     @          @      Programa IEL Mentoria             @@@@@@
@@@@@@    @     @          @      para Mulheres                     @@@@@@
@@@@@@    @     @@@@@@     @      Roda da Vida Empreendedora        @@@@@@
@@@@@@    @     @@@@@@     @                                        @@@@@@
@@@@@@    @     @          @      Instituto Euvaldo Lodi            @@@@@@
@@@@@@    @     @          @      CNI Liderança Feminina            @@@@@@
@@@@@@   @@@    @@@@@@@@   @@@@@@@@  na Indústria                   @@@@@@
@@@@@@   @@@    @@@@@@@@   @@@@@@@@                                 @@@@@@
@@@@@@                                                              @@@@@@
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
`;

console.log(
  banner,
  'color:#1a3b8c;font-weight:900;font-family:monospace;font-size:11px;line-height:1.4'
);

bootstrapApplication(App, appConfig)
  .catch((err) => console.error(err));
