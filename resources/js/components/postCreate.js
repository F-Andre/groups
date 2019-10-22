import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LoadModal from './loadModal';

function ArticleTitre(props) {
  const titleClass = props.value.length <= 6 ? 'form-control is-invalid' : 'form-control'
  return (
    <input
      type="text"
      name="titre"
      id="titre"
      value={props.value}
      onChange={props.onChange}
      className={titleClass}
    />
  );
}

export default class ArticleForm extends Component {
  constructor(props) {
    super(props)
    this.state = {
      titreValue: '',
      textValue: '',
      imgSrc: '',
      imgSize: 0,
      imageDeleted: '',
      buttonName: 'Ajouter une image',
      spinner: '',
    }
    this.handleChangeTitre = this.handleChangeTitre.bind(this);
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

  handleChangeTitre(event) {
    this.setState({ titreValue: event.target.value })
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
        buttonName: 'Modifier l\'image',
      });
    };
    reader.readAsDataURL(file);
  }

  handleDeleteImage() {
    this.setState({
      imgSrc: '',
      imageDeleted: true,
      buttonName: 'Ajouter une image',
    })
  }

  render() {
    const imageSizeMax = 20971520
    const imageClass = this.state.imgSize > imageSizeMax ? 'form-control is-invalid' : 'form-control'
    const disabledState = this.state.titreValue.length <= 6 ? true : this.state.textValue.length <= 10 ? true : this.state.imgSize > imageSizeMax ? true : false
    const submitClass = !disabledState ? "btn btn-primary" : "btn btn-secondary disabled"
    const disableDelete = this.state.imgSrc.length > 1 ? "btn btn-danger float-right" : "btn btn-danger float-right disabled"
    const contenuClass = this.state.textValue.length > 10 ? 'form-control' : this.state.textValue.length == 0 ? 'form-control' : 'form-control is-invalid'

    return (
      <div className="form-group">
        <div className="form-group">
          <label htmlFor="titre">Titre:</label>
          <ArticleTitre value={this.state.titreValue} onChange={this.handleChangeTitre} />
          <div className="invalid-feedback">Ecrivez un titre d'au moins 6 caractères.</div>
        </div>
        <div className="form-group">
          <label htmlFor="contenu">Ecrivez votre texte:</label>
          <textarea className={contenuClass} name="contenu" id="contenu" value={this.state.textValue} hidden />
          <iframe id="editor_iframe" className="postIframe" src="/editor_iframe.html"></iframe>
          <div className="invalid-feedback">Ecrivez un texte d'au moins 10 caractères.</div>
        </div>
        <div id="divImage" className="form-group">
          <img className="img-fluid text-center" src={this.state.imgSrc} />
          <input id="imageDeleted" type="text" className="disabled" name="imageDeleted" value={this.state.imageDeleted} />
          <input id="image" name="image" type="file" className={imageClass} accept=".JPG, .PNG, .SVG" ref={this.fileInput} onChange={this.handleChangeImage} />
          <div className="invalid-feedback">L'image doit être aux formats jpg, png ou svg et avoir une taille max de 20Mo.</div>
          <label className="btn btn-success" htmlFor="image">{this.state.buttonName}</label>
          <a id="btnDeleteImage" className={disableDelete} onClick={this.handleDeleteImage}>Effacer l'image</a>
        </div>
        <LoadModal />
        <button type="submit" data-toggle="modal" data-target="#loadModalDiv" className={submitClass} disabled={disabledState}>Poster l'article</button>
      </div>
    )
  }
}

if (document.getElementById('articleForm')) {
  ReactDOM.render(<ArticleForm />, document.getElementById('articleForm'));
}
