const fse = require('fs-extra');
const path = require('path');

const nodeModulesDir = path.join(__dirname, 'node_modules');
const publicDir = path.join(__dirname, 'public/plugin/node');

fse.emptyDirSync(path.join(publicDir, 'jquery'));
fse.copySync(path.join(nodeModulesDir, 'jquery', 'dist'), path.join(publicDir, 'jquery'), { overwrite: true });

fse.emptyDirSync(path.join(publicDir, 'tinymce'));
fse.copySync(path.join(nodeModulesDir, 'tinymce'), path.join(publicDir, 'tinymce'), { overwrite: true });

fse.emptyDirSync(path.join(publicDir, 'tinymce-jquery'));
fse.copySync(path.join(nodeModulesDir, '@tinymce', 'tinymce-jquery', 'dist'), path.join(publicDir, 'tinymce-jquery'), { overwrite: true });

fse.emptyDirSync(path.join(publicDir, 'filepond'));
fse.copySync(path.join(nodeModulesDir, 'filepond', 'dist'), path.join(publicDir, 'filepond'), { overwrite: true });

fse.emptyDirSync(path.join(publicDir, 'filepond-plugin-file-validate-type'));
fse.copySync(path.join(nodeModulesDir, 'filepond-plugin-file-validate-type', 'dist'), path.join(publicDir, 'filepond-plugin-file-validate-type'), { overwrite: true });

fse.emptyDirSync(path.join(publicDir, 'bootstrap-daterangepicker'));
fse.copySync(path.join(nodeModulesDir, 'bootstrap-daterangepicker'), path.join(publicDir, 'bootstrap-daterangepicker'), { overwrite: true });

fse.emptyDirSync(path.join(publicDir, 'select2'));
fse.copySync(path.join(nodeModulesDir, 'select2', 'dist'), path.join(publicDir, 'select2'), { overwrite: true });

fse.emptyDirSync(path.join(publicDir, 'sweetalert2'));
fse.copySync(path.join(nodeModulesDir, 'sweetalert2', 'dist'), path.join(publicDir, 'sweetalert2'), { overwrite: true });

fse.emptyDirSync(path.join(publicDir, 'bootstrap-tagsinput'));
fse.copySync(path.join(nodeModulesDir, 'bootstrap-tagsinput', 'dist'), path.join(publicDir, 'bootstrap-tagsinput'), { overwrite: true });