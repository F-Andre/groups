import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LoadModal from './loadModal';

function ArticleTitre(props) {
  const titleClass = props.value.length <= 6 ? 'form-control is-invalid' : 'form-control'
  const valueTitre = props.value.length > 0 ? props.value : ''
  return (
    <input
      type="text"
      className={titleClass}
      name="titre"
      id="titre"
      value={valueTitre}
      onChange={props.onChange}
    />
  );
}
export default class ArticleForm extends Component {
  constructor(props) {
    super(props)
    this.state = {
      titreValue: titre,
      textValue: contenu,
      imgSrc: image == '/storage/0' ? '' : image,
      imgSize: 0,
      imageDeleted: '',
      modified: false,
    }
    this.handleChangeTitre = this.handleChangeTitre.bind(this);
    this.handleLoad = this.handleLoad.bind(this);
    this.handleChangeImage = this.handleChangeImage.bind(this);
    this.handleDeleteImage = this.handleDeleteImage.bind(this);
    this.fileInput = React.createRef();
  }

  componentDidMount() {
    window.addEventListener('message', (e) => {
      if (typeof e.data == "string") {
        const text = e.data;
        this.setState({
          textValue: text,
          modified: true
        })
      }
    })
  }

  componentDidUpdate() {
    twemoji.parse(document.body);
  }

  handleChangeTitre(event) {
    this.setState({
      titreValue: event.target.value,
      modified: true
    })
  }

  handleLoad() {
    document.querySelector('#editor_iframe').contentWindow.postMessage(contenu, '*');
  }

  handleChangeImage() {
    const reader = new FileReader();
    let file = this.fileInput.current.files[0];
    let fileSrc = '', fileSize = file.size;
    reader.onload = (e) => {
      fileSrc = e.target.result;
      this.setState({
        imgSrc: fileSrc,
        imgSize: fileSize,
        modified: true
      });
    };
    reader.readAsDataURL(file);
  }

  handleDeleteImage() {
    this.setState({
      imgSrc: '',
      modified: true,
      imageDeleted: true,
    })
  }

  render() {
    const imageSizeMax = 20971520
    const contenuClass = this.state.textValue.length > 10 ? 'form-control' : this.state.textValue.length == 0 ? 'form-control' : 'form-control is-invalid'
    const disableState = this.state.modified ? false : true
    const submitClass = disableState ? "btn btn-secondary disabled" : "btn btn-primary"
    const disableDelete = this.state.imgSrc.length > 1 ? "btn btn-danger float-right" : "btn btn-danger float-right disabled"
    const imageClass = this.state.imgSize > imageSizeMax ? 'form-control is-invalid' : 'form-control'

    return (
      <div>
        <div className="form-group">
          <label htmlFor="titre">Titre:</label>
          <ArticleTitre value={this.state.titreValue} onChange={this.handleChangeTitre} />
          <div className="invalid-feedback">Ecrivez un titre d'au moins 6 caractères.</div>
        </div>
        <div className="form-group">
          <label htmlFor="contenu">Ecrivez votre texte:</label>
          <textarea className={contenuClass} name="contenu" id="contenu" value={this.state.textValue} readOnly hidden />
          <iframe id="editor_iframe" className="postIframe" src="/editor_iframe.html" onLoad={this.handleLoad}></iframe>
          <div className="invalid-feedback">Ecrivez un texte d'au moins 10 caractères.</div>
        </div>
        <div id="divImage" className="form-group d-flex flex-column">
          <div className="align-self-center">
            <img className="img-fluid" src={this.state.imgSrc} />
          </div>
          <input id="imageDeleted" type="text" className="disabled" name="imageDeleted" value={this.state.imageDeleted} readOnly hidden />
          <input id="image" name="image" type="file" className={imageClass} accept=".JPG, .PNG, .SVG" ref={this.fileInput} onChange={this.handleChangeImage} />
          <div className="invalid-feedback">L'image doit être aux formats jpg, png ou svg et avoir une taille max de 20Mo.</div>
          <div className="d-flex justify-content-between flex-wrap">
            <label htmlFor="image" className="btn btn-secondary mb-2">Modifier l'image</label>
            <a id="btnDeleteImage" className={disableDelete} onClick={this.handleDeleteImage}>Effacer l'image</a>
          </div>
        </div>
        <LoadModal />
        <button type="submit" data-toggle="modal" data-target="#loadModalDiv" className={submitClass} disabled={disableState}>Enregistrer</button>
      </div>
    )
  }
}

if (document.getElementById('postEditForm')) {
  ReactDOM.render(<ArticleForm />, document.getElementById('postEditForm'));
}
