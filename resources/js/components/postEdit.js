import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSpinner } from '@fortawesome/free-solid-svg-icons';

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

function ArticleText(props) {
    const contenuClass = props.value.length <= 10 ? 'form-control is-invalid' : 'form-control'
    const valueContenu = props.value.length > 0 ? props.value : ''
    return (
        <textarea
            className={contenuClass}
            name="contenu"
            id="contenu"
            rows="5"
            value={valueContenu}
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
            modified: false,
            imageDeleted: '',
        }
        this.handleChangeTitre = this.handleChangeTitre.bind(this);
        this.handleChangeText = this.handleChangeText.bind(this);
        this.handleChangeImage = this.handleChangeImage.bind(this);
        this.handleDeleteImage = this.handleDeleteImage.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.fileInput = React.createRef();
    }

    handleChangeTitre(event) {
        this.setState({
            titreValue: event.target.value,
            modified: true
        })
    }

    handleChangeText(event) {
        this.setState({
            textValue: event.target.value,
            modified: true
        })
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
              modified: true,
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

    handleSubmit() {
        const element = <FontAwesomeIcon icon={faSpinner} pulse />
        this.setState({
            spinner: element,
        })
    }

    render() {
        const imageSizeMax = 4718592
        const disabledState = !this.state.modified ? true : this.state.titreValue.length <= 6 ? true : this.state.textValue.length <= 10 ? true : this.state.imgSize > imageSizeMax ? true : false
        const submitClass = !disabledState ? "btn btn-primary" : "btn btn-secondary disabled"
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
                    <ArticleText value={this.state.textValue} onChange={this.handleChangeText} />
                    <div className="invalid-feedback">Ecrivez un texte d'au moins 10 caractères.</div>
                </div>
                <div id="divImage" className="form-group">
                    <div className="col-12 text-center mt-2">
                        <img className="img-fluid" src={this.state.imgSrc} />
                        <input id="imageDeleted" type="text" className="disabled" name="imageDeleted" value={this.state.imageDeleted} />
                    </div>
                    <input id="image" name="image" type="file" className={imageClass} accept=".JPG, .PNG, .GIF" ref={this.fileInput} onChange={this.handleChangeImage} />
                    <div className="invalid-feedback">L'image doit être aux formats jpg, png ou gif et avoir une taille max de 4.5Mo.</div>
                    <label htmlFor="image" className="btn btn-secondary m-0">Modifier l'image</label>
                    <a id="btnDeleteImage" className={disableDelete} onClick={this.handleDeleteImage}>Effacer l'image</a>
                </div>
                <button type="submit" onClick={this.handleSubmit} className={submitClass} disabled={disabledState}>Modifier {this.state.spinner}</button>
            </div>
        )
    }
}

if (document.getElementById('postEditForm')) {
    ReactDOM.render(<ArticleForm />, document.getElementById('postEditForm'));
}
