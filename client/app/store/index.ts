import { createPinia } from 'pinia'
import { createPersistedStatePlugin } from 'pinia-plugin-persistedstate-2'

const pinia = createPinia();
const persistedState = createPersistedStatePlugin({});

pinia.use(persistedState);

export { pinia };