const { contextBridge, ipcRenderer } = require('electron');

contextBridge.exposeInMainWorld('qpos', {
  onStatus: (cb) => ipcRenderer.on('status', (_e, msg) => cb(msg)),
  onError:  (cb) => ipcRenderer.on('error',  (_e, msg) => cb(msg)),
});
