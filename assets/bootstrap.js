import {
    startStimulusApp,
    // registerControllers,
} from 'vite-plugin-symfony/stimulus/helpers';

const app = startStimulusApp();
app.debug = false;

// registerControllers(
//     app,
//     import.meta.glob("./controllers/*.js", {
//         query: "?stimulus",
//         /**
//          * always true, the `lazy` behavior is managed internally with
//          * import.meta.stimulusFetch (see reference)
//          */
//         eager: true,
//     }),
// );
