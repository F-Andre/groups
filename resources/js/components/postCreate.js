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

function ArticleText(props) {
    const contenuClass = props.value.length <= 10 ? 'form-control is-invalid' : 'form-control'
    return (
        <textarea
            name="contenu"
            id="contenu"
            rows="5"
            value={props.value}
            onChange={props.onChange}
            className={contenuClass}
            hidden
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
            spinner: '',
        }
        this.handleChangeTitre = this.handleChangeTitre.bind(this);
        this.handleChangeText = this.handleChangeText.bind(this);
        this.handleChangeImage = this.handleChangeImage.bind(this);
        this.fileInput = React.createRef();
    }

    componentDidMount() {
        setTimeout(() => {
          window.addEventListener('message', (e) => {
            this.setState({
              textValue: e.data,
              modified: true
            })
          }, 500)
        })
      }

    handleChangeTitre(event) {
        this.setState({ titreValue: event.target.value })
    }

    handleChangeText(event) {
        this.setState({ textValue: event.target.value })
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
            });
        };
        reader.readAsDataURL(file);
    }

    render() {
        const imageSizeMax = 4718592
        const imageClass = this.state.imgSize > imageSizeMax ? 'form-control is-invalid' : 'form-control'
        const disabledState = this.state.titreValue.length <= 6 ? true : this.state.textValue.length <= 10 ? true : this.state.imgSize > imageSizeMax ? true : false
        const submitClass = !disabledState ? "btn btn-primary" : "btn btn-secondary disabled"

        return (
            <div className="form-group">
                <div className="form-group">
                    <label htmlFor="titre">Titre:</label>
                    <ArticleTitre value={this.state.titreValue} onChange={this.handleChangeTitre} />
                    <div className="invalid-feedback">Ecrivez un titre d'au moins 6 caractères.</div>
                </div>
                <div className="form-group">
                    <label htmlFor="contenu">Ecrivez votre texte:</label>
                    <ArticleText value={this.state.textValue} onChange={this.handleChangeText} />
                    <iframe id="editor_iframe" className="postIframe" src="/editor_iframe.html"></iframe>
                    <div className="invalid-feedback">Ecrivez un texte d'au moins 10 caractères.</div>
                </div>
                <div id="divImage" className="form-group">
                    <img className="img-fluid text-center" src={this.state.imgSrc} />
                    <input id="image" name="image" type="file" className={imageClass} accept=".JPG, .PNG, .GIF" ref={this.fileInput} onChange={this.handleChangeImage} />
                    <div className="invalid-feedback">L'image doit être aux formats jpg, png ou gif et avoir une taille max de 4.5Mo.</div>
                    <label className="btn btn-success" htmlFor="image">Ajouter une image</label>
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
